<?php

namespace Tests\Feature;

use App\Comment;
use App\Http\Resources\Notification;
use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Carbon\Carbon;
use Faker\Generator as Faker;


use App\User;


class FeatureTest extends TestCase
{
    use RefreshDatabase;

    //It will give error in JSon if endOfMonth and tomorrow in the same week
    /** @test */
    public function all_birthdays_can_be_filtered()
    {
        $user1 = factory(User::class)->create(['birthday' => Carbon::today()]);
        $user2 = factory(User::class)->create(['birthday' => Carbon::tomorrow()]);
        $user3 = factory(User::class)->create(['birthday' => Carbon::now()->endOfMonth()]);

        $this->assertCount(3, User::all());

        $this->actingAs($user3, 'api'); //Randomly login with any user

        $response = $this->post('/api/filter-birthdays')->assertStatus(200);

        $response->assertJson([
            'today' => [
                [
                    'id' => $user1->id,
                    'name' => $user1->name,
                    'email' => $user1->email,
                    'city' => $user1->city,
                    'gender' => $user1->gender,
                    'birthday' => [
                        'when' => $user1->birthday->format('d') - Carbon::now()->format('d'),
                        'age' => Carbon::now()->format('Y') - $user1->birthday->format('Y'),
                        'day_name' => $user1->birthday->format('l'),
                        'day' => $user1->birthday->day,
                        'month' => $user1->birthday->month,
                        'year' => $user1->birthday->year,
                    ],
                    'interest' => $user1->interest,
                    'about' => $user1->about,

                    'friendship' => null,

                    'cover_image' => [
                        'path' => 'uploadedAvatars/cover.jpg',
                        'width' => 1500,
                        'height' => 500,
                        'type' => 'cover',
                    ],

                    'profile_image' => [
                        'path' => 'uploadedAvatars/profile.jpg',
                        'width' => 750,
                        'height' => 750,
                        'type' => 'profile',
                    ],

                    'path' => $user1->path
                ],
            ],
            'week' => [
                [
                    'id' => $user2->id,
                    'name' => $user2->name,
                    'email' => $user2->email,
                    'city' => $user2->city,
                    'gender' => $user2->gender,
                    'birthday' => [
                        'when' => $user2->birthday->format('d') - Carbon::now()->format('d'),
                        'age' => Carbon::now()->format('Y') - $user2->birthday->format('Y'),
                        'day_name' => $user2->birthday->format('l'),
                        'day' => $user2->birthday->day,
                        'month' => $user2->birthday->month,
                        'year' => $user2->birthday->year,
                    ],
                    'interest' => $user2->interest,
                    'about' => $user2->about,

                    'friendship' => null,

                    'cover_image' => [
                        'path' => 'uploadedAvatars/cover.jpg',
                        'width' => 1500,
                        'height' => 500,
                        'type' => 'cover',
                    ],

                    'profile_image' => [
                        'path' => 'uploadedAvatars/profile.jpg',
                        'width' => 750,
                        'height' => 750,
                        'type' => 'profile',
                    ],

                    'path' => $user2->path
                ]
            ],
            'month' => [
                [
                    'id' => $user3->id,
                    'name' => $user3->name,
                    'email' => $user3->email,
                    'city' => $user3->city,
                    'gender' => $user3->gender,
                    'birthday' => [
                        'when' => $user3->birthday->format('d') - Carbon::now()->format('d'),
                        'age' => Carbon::now()->format('Y') - $user3->birthday->format('Y'),
                        'day_name' => $user3->birthday->format('l'),
                        'day' => $user3->birthday->day,
                        'month' => $user3->birthday->month,
                        'year' => $user3->birthday->year,
                    ],
                    'interest' => $user3->interest,
                    'about' => $user3->about,

                    'friendship' => null,

                    'cover_image' => [
                        'path' => 'uploadedAvatars/cover.jpg',
                        'width' => 1500,
                        'height' => 500,
                        'type' => 'cover',
                    ],

                    'profile_image' => [
                        'path' => 'uploadedAvatars/profile.jpg',
                        'width' => 750,
                        'height' => 750,
                        'type' => 'profile',
                    ],

                    'path' => $user3->path
                ]
            ],
        ]);
    }

    /** @test */
    public function auth_user_can_tag_a_friend_in_comment()
    {
        $this->actingAs($user1 = factory(User::class)->create(), 'api'); //It just logs in the user
        $user2 = factory(User::class)->create(['name' => 'jay']);

        $post = factory(Post::class)->create(['id' => 123]);

        $response = $this->post('/api/posts/' . $post->id . '/comments', ['body' => '@jay, hello how are you mate?']);

        $response->assertStatus(200);

        $comment = Comment::first();

        $this->assertCount(1, Comment::all());

        $this->assertEquals($user1->id, $comment->user_id);
        $this->assertEquals($post->id, $comment->post_id);

        $response->assertJson([
            'data' => [
                [
                    'body' => '@jay, hello how are you mate?',
                    'post_id' => $post->id,
                    'updated_at' => now()->diffForHumans(),
                    'commented_by' => [
                        'name' => $user1->name,
                        'email' => $user1->email
                    ],

                    'favourites' => [],
                    'user_favourited' => false,
                    'favourited_type' => [],

                    'tag' => [
                        'newBody' => ['@', ', hello how are you mate?'],
                        'taggedUserID'=> $user2->id,
                        'taggedUserName'=> $user2->name,
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
    public function auth_user_can_post_on_friends_wall()
    {
        $this->actingAs($user1 = factory(User::class)->create(), 'api'); //Randomly login with any user
        $user2 = factory(User::class)->create();

        $this->assertCount(2, User::all());

        $response = $this->post('/api/wish-birthday', ['body' => 'Hey mate', 'friend_id' => $user2->id])->assertStatus(201);

        $post = Post::first();

        $response->assertJson([
            'data' => [
                'id' => $post->id,
                'body' => 'Hey mate',
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
    public function auth_user_can_fetch_read_and_unread_notifications()
    {
        $this->withExceptionHandling();
        $this->actingAs($user1 = factory(User::class)->create(), 'api'); //Randomly login with any user
        $user2 = factory(User::class)->create(['name' => 'jay']);

        //Post on friend's wall
        $this->post('/api/wish-birthday', ['body' => 'Hey mate', 'friend_id' => $user2->id])->assertStatus(201);

        $post1 = Post::first();
        $post2 = factory(Post::class)->create(['user_id' => $user2->id]);

        //Like that Post
        $this->post('/api/posts/' . $post2->id . '/like-dislike')->assertStatus(200);

        //Comment on that post
        $this->post('/api/posts/' . $post2->id . '/comments', ['body' => 'hello how are you mate?'])->assertStatus(200);

        //Share a Post
        $this->post('/api/share-post', [
            'body' => 'A new body for shared post',
            'repost_id' => $post2->id,
            'user_id' => $user1->id
        ]);

        //Fetch all notifications
        $this->actingAs($user2, 'api');

        $response = $this->post('/api/notifications')->assertStatus(200);
        $response2 = $response->decodeResponseJson();

        $this->assertCount(4, $response2['all']);
        $this->assertCount(0, $response2['read']);
        $this->assertCount(4, $response2['unread']);
    }
}
