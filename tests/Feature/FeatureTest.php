<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Carbon\Carbon;

use App\User;


class FeatureTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function all_birthdays_can_be_filtered()
    {
        $user1 = factory(User::class)->create(['birthday' => '1996/08/11']);
        $user2 = factory(User::class)->create(['birthday' => '1996/08/14']);
        $user3 = factory(User::class)->create(['birthday' => '1996/08/25']);

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

                    'friendship' => [],

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

                    'friendship' => [],

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

                    'friendship' => [],

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
}
