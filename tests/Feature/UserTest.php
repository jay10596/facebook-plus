<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Http\Resources\UserResource;
use Tests\TestCase;
use Carbon\Carbon;

use App\Post;
use App\User;
use App\Friend;
use App\Avatar;


class UserTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    /** @test */
    public function auth_user_can_be_fetched()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $response = $this->post('/api/me');

        $response->assertStatus(200);

        $response->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'gender' => $user->gender,
            'birthday' => [
                'when' => $user->birthday->format('d') - Carbon::now()->format('d'),
                'age' => Carbon::now()->format('Y') - $user->birthday->format('Y'),
                'day_name' => $user->birthday->format('l'),
                'day' => $user->birthday->day,
                'month' => $user->birthday->month,
                'year' => $user->birthday->year,
            ],
            'interest' => $user->interest,
            'about' => $user->about,

            'friendship' => [],

            //This is default settings when no cover pic is uploaded
            'cover_image' => [
                'path' => 'uploadedAvatars/cover.jpg',
                'width' => 1500,
                'height' => 500,
                'type' => 'cover',
            ],

            //This is default settings when no profile pic is uploaded
            'profile_image' => [
                'path' => 'uploadedAvatars/profile.jpg',
                'width' => 750,
                'height' => 750,
                'type' => 'profile',
            ],

            'path' => $user->path
        ]);
    }

    /** @test */
    public function auth_user_can_check_user_profiles_and_posts()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $posts = factory(Post::class, 2)->create(['user_id' => $user->id]);

        $response = $this->get('/api/users/' . $user->id);

        $response->assertStatus(200);

        $response->assertJson([
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'city' => $user->city,
                'gender' => $user->gender,
                'birthday' => [
                    'when' => $user->birthday->format('d') - Carbon::now()->format('d'),
                    'age' => Carbon::now()->format('Y') - $user->birthday->format('Y'),
                    'day_name' => $user->birthday->format('l'),
                    'day' => $user->birthday->day,
                    'month' => $user->birthday->month,
                    'year' => $user->birthday->year,
                ],
                'interest' => $user->interest,
                'about' => $user->about,

                'friendship' => [],

                //This is default settings when no cover pic is uploaded
                'cover_image' => [
                    'path' => 'uploadedAvatars/cover.jpg',
                    'width' => 1500,
                    'height' => 500,
                    'type' => 'cover',
                ],

                //This is default settings when no profile pic is uploaded
                'profile_image' => [
                    'path' => 'uploadedAvatars/profile.jpg',
                    'width' => 750,
                    'height' => 750,
                    'type' => 'profile',
                ],

                'path' => $user->path
            ],
            [
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

                        'posted_by' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
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

                        'posted_on' => null,

                        'posted_by' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                        ],

                        'path' => $posts->last()->path
                    ]
                ],
                'links' => [
                    'self' => '/posts'
                ]
            ]
        ]);
    }

    /** @test */
    public function auth_user_can_upload_avatar()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $file = UploadedFile::fake()->image('image.jpg');

        $response = $this->post('/api/upload-avatars', [
            'avatar' => $file,
            'width' => '850',
            'height' => '300',
            'type' => 'cover'
        ])->assertStatus(201);

        Storage::disk('public')->assertExists('uploadedAvatars/' . $file->hashName());

        $avatar = Avatar::first();

        $this->assertEquals('uploadedAvatars/' . $file->hashName(), $avatar->path);
        $this->assertEquals('850', $avatar->width);
        $this->assertEquals('300', $avatar->height);
        $this->assertEquals('cover', $avatar->type);

        $response->assertJson([
            'data' => [
                'path' => $avatar->path,
                'width' => $avatar->width,
                'height' => $avatar->height,
                'type' => $avatar->type,
            ]
        ]);
    }

    /** @test */
    public function users_are_fetched_with_their_avatars()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->post('/api/upload-avatars', [
            'avatar' => $file,
            'width' => 850,
            'height' => 300,
            'type' => 'cover'
        ])->assertStatus(201);

        $this->post('/api/upload-avatars', [
            'avatar' => $file,
            'width' => 400,
            'height' => 400,
            'type' => 'profile'
        ])->assertStatus(201);

        $uploadedAvatars = Avatar::all();
        $coverAvatar = $uploadedAvatars[0];
        $profileAvatar = $uploadedAvatars[1];

        $response = $this->get('/api/users/' . $user->id);

        $response->assertJson([
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'city' => $user->city,
                'gender' => $user->gender,
                'birthday' => [
                    'when' => $user->birthday->format('d') - Carbon::now()->format('d'),
                    'age' => Carbon::now()->format('Y') - $user->birthday->format('Y'),
                    'day_name' => $user->birthday->format('l'),
                    'day' => $user->birthday->day,
                    'month' => $user->birthday->month,
                    'year' => $user->birthday->year,
                ],
                'interest' => $user->interest,
                'about' => $user->about,

                'friendship' => [],

                'cover_image' => [
                    'path' => $coverAvatar->path,
                    'width' => $coverAvatar->width,
                    'height' => $coverAvatar->height,
                    'type' => $coverAvatar->type,
                ],

                'profile_image' => [
                    'path' => $profileAvatar->path,
                    'width' => $profileAvatar->width,
                    'height' => $profileAvatar->height,
                    'type' => $profileAvatar->type,
                ],

                'path' => $user->path
            ],
            [
                'data' =>[],
                'links' => [
                    'self' => '/posts'
                ]
            ]
        ]);
    }

    /** @test */
    public function auth_user_can_send_friend_request()
    {
        $this->actingAs($user1 = factory(User::class)->create(), 'api'); //It just logs in the user

        $user2 = factory(User::class)->create();

        $response = $this->post('/api/send-request', ['friend_id' => $user2->id]);

        $response->assertStatus(200);

        $friendRequest = Friend::first(); //To grab first row from the friend's table

        $this->assertNotNull($friendRequest);
        $this->assertEquals($user2->id, $friendRequest->friend_id);
        $this->assertEquals($user1->id, $friendRequest->user_id);

        $response->assertJson([
            'data' => [
                'id' => $friendRequest->id,
                'status' => $friendRequest->status, //NULL
                'confirmed_at' => $friendRequest->confirmed_at, //NULL
                'path' => '/users/'.$friendRequest->friend_id
            ]
        ]);
    }

    /** @test */
    public function auth_user_can_accept_friend_request()
    {
        $this->actingAs($user1 = factory(User::class)->create(), 'api'); //It just logs in the user

        $user2 = factory(User::class)->create();

        $response1 = $this->post('/api/send-request', ['friend_id' => $user2->id]);

        $this->actingAs($user2, 'api'); //It just logs in the user

        $response2 = $this->post('/api/confirm-request', ['user_id' => $user1->id]);

        $response2->assertStatus(200);

        $friendRequest = Friend::first(); //To grab first row from the friend's table

        $this->assertNotNull($friendRequest->confirmed_at);
        $this->assertNotNull($friendRequest->status);

        $this->assertInstanceOf(Carbon::class, $friendRequest->confirmed_at);

        $this->assertEquals(now()->startOfSecond(), $friendRequest->confirmed_at);

        $this->assertEquals(1, $friendRequest->status);

        $response2->assertJson([
            'data' => [
                'id' => $friendRequest->id,
                'status' => $friendRequest->status,
                'confirmed_at' => $friendRequest->confirmed_at->diffForHumans(),
                'friend_id' => $friendRequest->friend_id,
                'user_id' => $friendRequest->user_id,
                'path' => '/users/'.$friendRequest->friend_id
            ]
        ]);
    }

    /** @test */
    public function only_valid_users_can_send_friend_request()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $response = $this->post('/api/send-request', ['friend_id' => 123]);

        $friendRequest = Friend::first(); //To grab first row from the friend's table

        $this->assertNull($friendRequest);

        $response->assertStatus(404);

        $response->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'User not found',
                'detail' => 'Unable to locate user with given information'
            ]
        ]);
    }

    /** @test */
    public function only_valid_friend_requests_can_be_accepted()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $response = $this->post('/api/confirm-request', ['user_id' => 123]);

        $friendRequest = Friend::first(); //To grab first row from the friend's table

        $this->assertNull($friendRequest);

        $response->assertStatus(404);

        $response->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'Friend Request not found',
                'detail' => 'Unable to locate friend request with given information'
            ]
        ]);
    }

    /** @test */
    public function third_party_user_cannot_accept_the_request()
    {
        $this->actingAs($user1 = factory(User::class)->create(), 'api'); //It just logs in the user

        $user2 = factory(User::class)->create();

        $response1 = $this->post('/api/send-request', ['friend_id' => $user2->id]);

        $response1->assertStatus(200);

        $user3 = factory(User::class)->create();

        $this->actingAs($user3, 'api');

        $response2 = $this->post('/api/confirm-request', ['user_id' => $user1->id]);

        $response2->assertStatus(404);

        $friendRequest = Friend::first();

        $this->assertEquals(null, $friendRequest->confirmed_at);

        $this->assertEquals(null, $friendRequest->status);

        $response2->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'Friend Request not found',
                'detail' => 'Unable to locate friend request with given information'
            ]
        ]);
    }

    /** @test */
    public function friend_id_is_required_to_send_request()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $response = $this->post('/api/send-request', ['friend_id' => '']);

        $response->assertStatus(422);

        $responseString = json_decode($response->getContent(), true); //true will convert the object into array

        $this->assertArrayHasKey('friend_id', $responseString['errors']['meta']);
    }

    /** @test */
    public function user_id_is_required_to_confirm_request()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $response = $this->post('/api/confirm-request', ['user_id' => '']);

        $response->assertStatus(422);

        $responseString = json_decode($response->getContent(), true); //true will convert the object into array

        $this->assertArrayHasKey('user_id', $responseString['errors']['meta']);
    }

    /** @test */
    public function friendships_can_be_fetched_in_the_profile()
    {
        $this->actingAs($user1 = factory(User::class)->create(), 'api'); //It just logs in the user

        $user2 = factory(User::class)->create();

        //Another way without post response
        $friendRequest = Friend::create([
            'user_id' => $user1->id,
            'friend_id' => $user2->id,
            'confirmed_at' => now()->subDay(),
            'status' => 1
        ]);

        $response = $this->get('/api/users/' . $user2->id);

        $response->assertStatus(200);

        $response->assertJson([
            [
                'id' => $user2->id,
                'name' => $user2->name,
                'email' => $user2->email,
                'friendship' => [
                    'confirmed_at' => '1 day ago'
                ],
                'path' => $user2->path
            ],
            [
                'data' =>[],
                'links' => [
                    'self' => '/posts'
                ]
            ]
        ]);
    }

    /** @test */
    public function inverse_friendships_can_be_fetched_in_the_profile()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user1 = factory(User::class)->create(), 'api'); //It just logs in the user

        $user2 = factory(User::class)->create();

        //Another way without post response
        $friendRequest = Friend::create([
            'friend_id' => $user1->id,
            'user_id' => $user2->id,
            'confirmed_at' => now()->subDay(),
            'status' => 1
        ]);

        $response = $this->get('/api/users/' . $user2->id);

        $response->assertStatus(200);

        $response->assertJson([
            [
                'id' => $user2->id,
                'name' => $user2->name,
                'email' => $user2->email,
                'friendship' => [
                    'confirmed_at' => '1 day ago'
                ],
                'path' => $user2->path
            ],
            [
                'data' =>[],
                'links' => [
                    'self' => '/posts'
                ]
            ]
        ]);
    }

    /** @test */
    public function auth_user_can_delete_friend_request()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user1 = factory(User::class)->create(), 'api'); //It just logs in the user

        $user2 = factory(User::class)->create();

        $response1 = $this->post('/api/send-request', ['friend_id' => $user2->id]);

        $this->actingAs($user2, 'api'); //It just logs in the user

        $response2 = $this->post('/api/delete-request', ['user_id' => $user1->id]);

        $response2->assertStatus(204);

        $friendRequest = Friend::first(); //To grab first row from the friend's table

        $this->assertNull($friendRequest);

        $response2->assertNoContent();
    }

    /** @test */
    public function third_party_user_cannot_delete_the_request()
    {
        $this->actingAs($user1 = factory(User::class)->create(), 'api'); //It just logs in the user

        $user2 = factory(User::class)->create();

        $response1 = $this->post('/api/send-request', ['friend_id' => $user2->id]);

        $response1->assertStatus(200);

        $user3 = factory(User::class)->create();

        $this->actingAs($user3, 'api');

        $response2 = $this->post('/api/delete-request', ['user_id' => $user1->id]);

        $response2->assertStatus(404);

        $friendRequest = Friend::first();

        $this->assertEquals(null, $friendRequest->confirmed_at);

        $this->assertEquals(null, $friendRequest->status);

        $response2->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'Friend Request not found',
                'detail' => 'Unable to locate friend request with given information'
            ]
        ]);
    }

    /** @test */
    public function user_id_is_required_to_delete_request()
    {
        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $response = $this->post('/api/delete-request', ['user_id' => '']);

        $response->assertStatus(422);

        $responseString = json_decode($response->getContent(), true); //true will convert the object into array

        $this->assertArrayHasKey('user_id', $responseString['errors']['meta']);
    }

    /** @test */
    public function auth_user_can_send_friend_request_only_once()
    {
        $this->actingAs($user1 = factory(User::class)->create(), 'api'); //It just logs in the user

        $user2 = factory(User::class)->create();

        $this->post('/api/send-request', ['friend_id' => $user2->id])->assertStatus(200);

        $this->post('/api/send-request', ['friend_id' => $user2->id])->assertStatus(200);

        $friendRequest = Friend::all(); //To grab first row from the friend's table

        $this->assertCount(1, $friendRequest);
    }

    /** @test */
    public function auth_user_can_edit_his_profile()
    {
        $this->withExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api'); //It just logs in the user

        $response = $this->put('/api/users/' . $user->id, ['gender' => 'female', 'city' => 'New City', 'about' => 'Edited info about me']);

        $response->assertStatus(201);

        //AuthController->Me() does not contain data[] but UserController->update() contains. Check both controller to understand the difference.
        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'city' => 'New City',
                'gender' => 'female',
                'birthday' => [
                    'when' => $user->birthday->format('d') - Carbon::now()->format('d'),
                    'age' => Carbon::now()->format('Y') - $user->birthday->format('Y'),
                    'day_name' => $user->birthday->format('l'),
                    'day' => $user->birthday->day,
                    'month' => $user->birthday->month,
                    'year' => $user->birthday->year,
                ],
                'interest' => $user->interest,
                'about' => 'Edited info about me',

                'friendship' => [],

                //This is default settings when no cover pic is uploaded
                'cover_image' => [
                    'path' => 'uploadedAvatars/cover.jpg',
                    'width' => 1500,
                    'height' => 500,
                    'type' => 'cover',
                ],

                //This is default settings when no profile pic is uploaded
                'profile_image' => [
                    'path' => 'uploadedAvatars/profile.jpg',
                    'width' => 750,
                    'height' => 750,
                    'type' => 'profile',
                ],

                'path' => $user->path
            ]
        ]);
    }
}
