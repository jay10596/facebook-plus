<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

use App\Comment;
use App\Post;
use App\User;
use App\Gif;


class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function posts_are_returned_with_comments()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $post = factory(Post::class)->create(['id' => 123, 'user_id' => $user->id]);

        $this->post('/api/posts/' . $post->id . '/comments', ['body' => 'A new comment here!'])->assertStatus(200);;

        $response = $this->get('/api/posts');

        $response->assertStatus(200);

        $comment = Comment::first();

        $this->assertCount(1, Comment::all());

        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($post->id, $comment->post_id);

        $response->assertJson([
            'data' => [
                [
                    'id' => 123,
                    'body' => $post->first()->body,
                    'user_id' => $post->first()->user_id,
                    'created_at' => $post->first()->created_at->diffForHumans(),

                    'comments' => [
                        'data' => [
                            [
                                'body' => 'A new comment here!',
                                'updated_at' => now()->diffForHumans(),
                                'commented_by' => [
                                    'name' => $user->name,
                                    'email' => $user->email
                                ],
                                'path' => '/posts/' . $post->id . '/comments/' . $comment->id,
                            ]
                        ],
                        'comment_count' => 1,
                        'links' => [
                            'self' => '/posts',
                        ],
                    ],

                    'likes' => [],

                    'pictures' => [],

                    'shared_post' => null,

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
    public function auth_user_can_create_a_text_comment()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $post = factory(Post::class)->create(['id' => 123]);

        $response = $this->post('/api/posts/' . $post->id . '/comments', ['body' => 'A new comment here!']);

        $response->assertStatus(200);

        $comment = Comment::first();

        $this->assertCount(1, Comment::all());

        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($post->id, $comment->post_id);

        $response->assertJson([
            'data' => [
                [
                    'body' => 'A new comment here!',
                    'post_id' => $post->id,
                    'updated_at' => now()->diffForHumans(),
                    'commented_by' => [
                        'name' => $user->name,
                        'email' => $user->email
                    ],
                    'path' => '/posts/' . $post->id . '/comments/' . $comment->id,
                ]
            ],
            'comment_count' => 1,
            'links' => [
                'self' => '/posts',
            ],
        ]);
    }

    /** @test */
    public function auth_user_can_create_a_gif_comment()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $post = factory(Post::class)->create(['id' => 123]);

        $file = UploadedFile::fake()->image('commentGif.jpg');

        $response = $this->post('/api/upload-gif', [
            'body' => 'A new comment here!',
            'gif' => $file, //Unlike Pictures and Images, here it would be only $file rather than [$file] because gif is inside the Comment Model and hasOne relationship.
            'post_id' => $post->id,
            'user_id' => $user->id,
            // Passing Height and Width from the Dropzone params.
            'width' => 250,
            'height' => 250,
        ])->assertStatus(201);

        Storage::disk('public')->assertExists('uploadedGifs/' . $file->hashName());

        $this->assertCount(1, Comment::all());

        $comment = Comment::first();

        //In GifController we are returning CommentResource rather than collection, which is why the Json will differ.
        $response->assertJson([
            'data' => [
                'body' => 'A new comment here!',
                'gif' => $comment->gif,
                'post_id' => $post->id,
                'updated_at' => now()->diffForHumans(),

                'favourites' => [],
                'user_favourited' => false,
                'favourited_type' => [],

                'commented_by' => [
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'path' => '/posts/' . $post->id . '/comments/' . $comment->id,
            ]
        ]);
    }

    /** @test */
    public function auth_user_can_edit_a_text_comment()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $post = factory(Post::class)->create(['id' => 123]);

        $comment = factory(Comment::class)->create(['id' => 123, 'post_id' => $post->id]);

        $response = $this->put('/api/posts/' . $post->id . '/comments/' . $comment->id, ['body' => 'An edited comment here!']);

        $response->assertStatus(200);

        $comment = Comment::first();

        $this->assertCount(1, Comment::all());

        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($post->id, $comment->post_id);
        $this->assertEquals($comment->body, 'An edited comment here!');

        $response->assertJson([
            'data' => [
                [
                    'body' => 'An edited comment here!',
                    'post_id' => 123,
                    'updated_at' => now()->diffForHumans(),
                    'commented_by' => [
                        'name' => $user->name,
                        'email' => $user->email
                    ],
                    'path' => '/posts/' . $post->id . '/comments/' . $comment->id,
                ]
            ],
            'comment_count' => 1,
            'links' => [
                'self' => '/posts',
            ],
        ]);
    }

    /** @test */
    /*public function auth_user_can_edit_a_gif_comment()
    {

    }*/

    /** @test */
    public function auth_user_can_delete_a_comment()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $post = factory(Post::class)->create(['id' => 123]);

        $comment = factory(Comment::class)->create(['id' => 123, 'post_id' => $post->id]);

        $response = $this->delete('/api/posts/' . $post->id . '/comments/' . $comment->id);

        $response->assertStatus(204);

        $this->assertCount(0, Comment::all());
    }

    /** @test */
    public function auth_user_can_favourite_a_comment()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api');

        $post = factory(Post::class)->create(['id' => 123]);

        $comment = factory(Comment::class)->create(['id' => 123, 'post_id' => $post->id]);

        $response = $this->post('/api/posts/' . $post->id . '/comments/' . $comment->id . '/favourite-unfavourite', ['type' => 2]);

        $response->assertStatus(200);

        $this->assertCount(1, $comment->favourites);

        $response->assertJson([
            'data' => [
                [
                    'type' => 2,
                    'comment_id' => $comment->id,
                    'user_id' => $user->id,
                    'created_at' => now()->diffForHumans(),
                ]
            ],
            'favourite_count' => 1,
            'links' => [
                'self' => '/posts',
            ],
        ]);
    }

    /** @test */
    public function body_is_required_for_a_text_comment()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $post = factory(Post::class)->create(['id' => 123]);

        $response = $this->post('/api/posts/' . $post->id . '/comments');

        $response->assertStatus(422);

        $responseString = json_decode($response->getContent(), true); //true will convert the object into array

        $this->assertArrayHasKey('body', $responseString['errors']['meta']);
    }

    /** @test */
    public function body_is_not_required_for_a_gif_comment()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $post = factory(Post::class)->create(['id' => 123]);

        $file = UploadedFile::fake()->image('commentGif.jpg');

        $response = $this->post('/api/upload-gif', [
            'body' => '', //Body is not required for comment and Dropzone will directly post the comment once gif is uploaded as body in comment table has been made nullable. User can't post an empty comment as well because without gif and body, the Post button won't show up to actually post the comment.
            'gif' => $file, //Unlike Pictures and Images, here it would be only $file rather than [$file] because gif is inside the Comment Model and hasOne relationship.
            'post_id' => $post->id,
            'user_id' => $user->id,
            // Passing Height and Width from the Dropzone params.
            'width' => 250,
            'height' => 250,
        ])->assertStatus(201);

        Storage::disk('public')->assertExists('uploadedGifs/' . $file->hashName());

        $this->assertCount(1, Comment::all());
    }
}
