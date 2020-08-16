<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Http\Resources\UserResource;
use Tests\TestCase;

use App\User;
use App\Post;
use App\Friend;
use App\Picture;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $server;

    protected $post;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install',['-vvv' => true]);

        $this->user = factory(User::class)->create();

        $this->token = $this->user->createToken('MyApp')->accessToken;

        $this->server = [
            'HTTP_Authorization' => 'Bearer '. $this->token
        ];

        Storage::fake('public');
    }

    private function postData()
    {
        return [
            'body' => 'This is a new post.',
        ];
    }

    /** @test */
    //actingAs is another way to login if you don't want pass the token
    public function auth_user_can_fetch_all_posts_including_his_friends()
    {
        $this->actingAs($user1 = factory(User::class)->create(), 'api'); //It just logs in the user

        $user2 = factory(User::class)->create();

        $posts = factory(Post::class, 2)->create(['user_id' => $user2->id]);

        Friend::create([
            'user_id' => $user1->id,
            'friend_id' => $user2->id,
            'confirmed_at' => now(),
            'status' => 1
        ]);

        $response = $this->get('/api/posts');

        $response->assertJson([
            'data' => [
                [
                    'id' => $posts->first()->id,
                    'body' => $posts->first()->body,
                    'user_id' => $posts->first()->user_id,
                    'created_at' => $posts->first()->created_at->diffForHumans(),

                    'comments' => [],

                    'likes' => [],

                    'pictures' => [],

                    'shared_post' => null,
                    'shared_count' => 0,

                    'posted_on' => null,

                    'posted_by' => [
                        'id' => $user2->id,
                        'name' => $user2->name,
                        'email' => $user2->email,
                    ],

                    'path' => $posts->first()->path
                ],
                [
                    'id' => $posts->last()->id,
                    'body' => $posts->last()->body,
                    'user_id' => $posts->last()->user_id,
                    'created_at' => $posts->last()->created_at->diffForHumans(),

                    'comments' => [],

                    'likes' => [],

                    'pictures' => [],

                    'shared_post' => null,
                    'shared_count' => 0,

                    'posted_on' => null,

                    'posted_by' => [
                        'id' => $user2->id,
                        'name' => $user2->name,
                        'email' => $user2->email,
                    ],

                    'path' => $posts->last()->path
                ]
            ],
            'links' => [
                'self' => '/posts'
            ]
        ]);
    }

    /** @test */
    public function auth_user_cannot_fetch_others_posts()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api');

        $user2 = factory(User::class)->create();

        $posts = factory(Post::class, 2)->create(['user_id' => $user2->id]); //These posts do not belong to logged in user

        $response = $this->get('/api/posts');

        $response->assertExactJson([
            'data' => [],
            'links' => [
                'self' => '/posts'
            ]
        ]);
    }

    /** @test */
    public function auth_user_can_create_text_post()
    {   //One way to create a post
        $post = factory(Post::class)->create(['user_id' => $this->user->id]);

        //Second way to create a post
        $response = $this->post('/api/posts', $this->postData(), $this->server);

        $response->assertStatus(201);

        $this->assertCount(2, Post::all());

        $posts = Post::all();
        $post = $posts->last();

        $this->assertEquals('This is a new post.', $post->body);
        $this->assertEquals($post->user_id, $this->user->id);

        $response->assertJson([
            'data' => [
                'id' => $post->id,
                'body' => $post->body,
                'user_id' => $post->user_id,
                'created_at' => $post->created_at->diffForHumans(),

                'comments' => [],

                'likes' => [],

                'pictures' => [],

                'shared_post' => null,
                'shared_count' => 0,

                'posted_on' => null,

                'posted_by' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ],

                'path' => $post->path
            ]
        ]);
    }

    /** @test */
    public function auth_user_can_create_single_picture_post()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $file = UploadedFile::fake()->image('postImage.jpg');

        $response = $this->post('/api/upload-pictures', [
            'body' => 'test Body',
            'picture' => [$file],
            'post_id' => null,
            'user_id' => $user->id
        ])->assertStatus(201);

        Storage::disk('public')->assertExists('uploadedPictures/' . $file->hashName());

        $this->assertCount(1, Post::all());
        $this->assertCount(1, Picture::all());

        $post = Post::first();
        $picture = Picture::first();

        $response->assertJson([
            'data' => [
                'id' => $post->id,
                'body' => $post->body,
                'user_id' => $post->user_id,
                'created_at' => $post->created_at->diffForHumans(),

                'comments' => [],

                'likes' => [],

                'pictures' => [
                    'data' => [
                        [
                            'id' => $picture->id,
                            'path' => $picture->path,
                        ]
                    ],

                    'picture_count' => 1,

                    'links' => [
                        'self' => '/posts'
                    ]
                ],

                'shared_post' => null,

                'posted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],

                'path' => $post->path
            ]
        ]);
    }

    /** @test */
    public function auth_user_can_create_multiple_pictures_post()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $file1 = UploadedFile::fake()->image('postImage1.jpg');
        $file2 = UploadedFile::fake()->image('postImage2.jpg');
        $file3 = UploadedFile::fake()->image('postImage3.jpg');

        $response = $this->post('/api/upload-pictures', [
            'body' => 'test Body',
            'picture' => [$file1, $file2, $file3],
            'post_id' => null,
            'user_id' => $user->id
        ])->assertStatus(201);

        Storage::disk('public')->assertExists('uploadedPictures/' . $file1->hashName());
        Storage::disk('public')->assertExists('uploadedPictures/' . $file2->hashName());
        Storage::disk('public')->assertExists('uploadedPictures/' . $file3->hashName());

        $this->assertCount(1, Post::all());
        $this->assertCount(3, Picture::all());

        $post = Post::first();
        $pictures = Picture::all();
        $picture1 = $pictures[0];
        $picture2 = $pictures[1];
        $picture3 = $pictures[2];

        $response->assertJson([
            'data' => [
                'id' => $post->id,
                'body' => $post->body,
                'user_id' => $post->user_id,
                'created_at' => $post->created_at->diffForHumans(),

                'comments' => [],

                'likes' => [],

                'pictures' => [
                    'data' => [
                        [
                            'id' => $picture1->id,
                            'path' => $picture1->path,
                        ],
                        [
                            'id' => $picture2->id,
                            'path' => $picture2->path,
                        ],
                        [
                            'id' => $picture3->id,
                            'path' => $picture3->path,
                        ],
                    ],

                    'picture_count' => 3,

                    'links' => [
                        'self' => '/posts'
                    ]
                ],

                'shared_post' => null,

                'posted_on' => null,

                'posted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],

                'path' => $post->path
            ]
        ]);
    }

    /** @test */
    public function auth_user_can_update_text_post()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $response = $this->put('/api/posts/' . $post->id, ['body' => 'An updated post']);

        $response->assertStatus(201);

        $post = Post::first();

        $this->assertEquals('An updated post', $post->body);

        $response->assertJson([
            'data' => [
                'id' => $post->id,
                'body' => 'An updated post',
                'user_id' => $post->user_id,
                'created_at' => $post->created_at->diffForHumans(),

                'comments' => [],

                'likes' => [],

                'pictures' => [],

                'shared_post' => null,
                'shared_count' => 0,

                'posted_on' => null,

                'posted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],

                'path' => $post->path
            ]
        ]);
    }

    /** @test */
    public function auth_user_can_update_post_with_picture()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $file = UploadedFile::fake()->image('postImage.jpg');

        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $response = $this->post('/api/upload-pictures', [
            'body' => 'An updated post',
            'picture' => [$file],
            'post_id' => $post->id,
            'user_id' => $user->id
        ])->assertStatus(201);

        Storage::disk('public')->assertExists('uploadedPictures/' . $file->hashName());

        $this->assertCount(1, Post::all());
        $this->assertCount(1, Picture::all());

        $post = Post::first();
        $picture = Picture::first();

        $response->assertJson([
            'data' => [
                'id' => $post->id,
                'body' => 'An updated post',
                'user_id' => $post->user_id,
                'created_at' => $post->created_at->diffForHumans(),

                'comments' => [],

                'likes' => [],

                'pictures' => [
                    'data' => [
                        [
                            'id' => $picture->id,
                            'path' => $picture->path,
                        ]
                    ],

                    'picture_count' => 1,

                    'links' => [
                        'self' => '/posts'
                    ]
                ],

                'shared_post' => null,
                'shared_count' => 0,

                'posted_on' => null,

                'posted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],

                'path' => $post->path
            ]
        ]);
    }

    /** @test */
    public function auth_user_can_delete_post()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $response = $this->delete('/api/posts/' . $post->id);

        $response->assertStatus(204);

        $posts = Post::all();

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function auth_user_can_like_a_post()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $post = factory(Post::class)->create(['id' => 123]);

        $response = $this->post('/api/posts/' . $post->id . '/like-dislike');

        $response->assertStatus(200);

        $this->assertCount(1, $user->likes);

        $response->assertJson([
            'data' => [
                [
                    'created_at' => now()->diffForHumans(),
                    'post_id' => $post->id,
                    'path' => '/posts/' . $post->id,
                ]
            ],
            'like_count' => 1,
            'user_liked' => true,
            'links' => [
                'self' => '/posts',
            ],
        ]);
    }

    /** @test */
    public function posts_are_returned_with_likes()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $post = factory(Post::class)->create(['id' => 123, 'user_id' => $user->id]);

        $this->post('/api/posts/' . $post->id . '/like-dislike')->assertStatus(200);;

        $response = $this->get('/api/posts');

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'id' => $post->first()->id,
                    'body' => $post->first()->body,
                    'user_id' => $post->first()->user_id,
                    'created_at' => $post->first()->created_at->diffForHumans(),

                    'comments' => [],

                    'likes' => [
                        'data' => [
                            [
                                'created_at' => now()->diffForHumans(),
                                'post_id' => $post->id,
                                'path' => '/posts/' . $post->id,
                            ]
                        ],
                        'like_count' => 1,
                        'user_liked' => true,
                        'links' => [
                            'self' => '/posts',
                        ]
                    ],

                    'pictures' => [],

                    'shared_post' => null,
                    'shared_count' => 0,

                    'posted_on' => null,

                    'posted_by' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],

                    'path' => $post->first()->path
                ],
            ],
            'links' => [
                'self' => '/posts',
            ],
        ]);
    }

    /** @test */
    public function auth_user_can_share_post()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $response = $this->post('/api/share-post', [
            'body' => 'A new body for shared post',
            'repost_id' => $post->id,
            'user_id' => $user->id
        ]);

        $response->assertStatus(201);

        $posts = Post::all();
        $post1 = $posts[0]; //$post which is created using factory above
        $post2 = $posts[1]; //Recently created shared post

        $this->assertCount(2, $posts);

        $response->assertJson([
            'data' => [
                'id' => $post2->id,
                'body' => 'A new body for shared post',
                'user_id' => $post2->user_id,
                'created_at' => $post2->created_at->diffForHumans(),

                'comments' => [],

                'likes' => [],

                'pictures' => [],

                'shared_post' => [
                    'id' => $post1->id,
                    'body' => $post1->body,
                    'user_id' => $post1->user_id,
                    'created_at' => $post1->created_at->diffForHumans(),

                    'comments' => [],

                    'likes' => [],

                    'pictures' => [
                        'data' => [],

                        'picture_count' => 0,

                        'links' => [
                            'self' => '/posts'
                        ]
                    ],

                    'posted_on' => null,

                    'posted_by' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ]
                ],
                'shared_count' => 0,

                'posted_on' => null,

                'posted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],

                'path' => $post2->path
            ]
        ]);

        //To check if the original post's shared count in increased on not
        $this->get('/api/posts')->assertJson([
        'data' => [
            [
                'id' => $post1->id,
                'body' => $post1->body,
                'user_id' => $post1->user_id,
                'created_at' => $post1->created_at->diffForHumans(),

                'comments' => [],

                'likes' => [],

                'pictures' => [],

                'shared_post' => [],
                'shared_count' => 1,

                'posted_on' => null,

                'posted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],

                'path' => $post1->path
            ],
            [
                'id' => $post2->id,
                'body' => $post2->body,
                'user_id' => $post2->user_id,
                'created_at' => $post2->created_at->diffForHumans(),

                'comments' => [],

                'likes' => [],

                'pictures' => [],

                'shared_post' => [
                    'id' => $post1->id,
                    'body' => $post1->body,
                    'user_id' => $post1->user_id,
                    'created_at' => $post1->created_at->diffForHumans(),

                    'comments' => [],

                    'likes' => [],

                    'pictures' => [
                        'data' => [],

                        'picture_count' => 0,

                        'links' => [
                            'self' => '/posts'
                        ]
                    ],

                    'posted_on' => null,

                    'posted_by' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ]
                ],
                'shared_count' => 0,

                'posted_on' => null,

                'posted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],

                'path' => $post2->path
            ],
        ],
        'links' => [
            'self' => '/posts'
        ]
    ]);;

    }

    /** @test */
    public function auth_user_can_create_text_post_on_newsfeed_of_other_user()
    {
        $this->actingAs($user1 = factory(User::class)->create(), 'api'); //It just logs in the user

        $user2 = factory(User::class)->create();

        $response = $this->post('/api/posts', ['body' => 'Happy birthday my friend', 'friend_id' => $user2->id, 'user_id' => $user1->id]);

        $response->assertStatus(201);

        $this->assertCount(1, Post::all());

        $post = Post::first();

        $this->assertEquals('Happy birthday my friend', $post->body);
        $this->assertEquals($post->friend_id, $user2->id);

        $response->assertJson([
            'data' => [
                'id' => $post->id,
                'body' => $post->body,
                'user_id' => $post->user_id,
                'created_at' => $post->created_at->diffForHumans(),

                'comments' => [],

                'likes' => [],

                'pictures' => [],

                'shared_post' => null,
                'shared_count' => 0,

                'posted_on' => [
                    'id' => $user2->id,
                    'name' => $user2->name,
                    'email' => $user2->email,
                ],

                'posted_by' => [
                    'id' => $user1->id,
                    'name' => $user1->name,
                    'email' => $user1->email,
                ],

                'path' => $post->path
            ]
        ]);
    }

    /** @test */
    public function body_is_required_for_a_post()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $post = factory(Post::class)->create(['id' => 123]);

        $response = $this->post('/api/posts');

        $response->assertStatus(422);

        $responseString = json_decode($response->getContent(), true); //true will convert the object into array

        $this->assertArrayHasKey('body', $responseString['errors']['meta']);
    }
}
