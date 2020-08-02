<?php

namespace Tests\Feature;

use App\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

use App\Item;
use App\Image;
use App\User;


class ItemTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;
    protected $item;
    protected $image;
    protected $file;

    //As we accept only items with images, in order to have a base item for delete, update and bookmark, we need to write whole test code of creating and storing the item with image. Therefore, using setUp() makes the code look cleaner.
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->user = factory(User::class)->create(), 'api');

        $this->category = factory(Category::class)->create();

        $this->file = UploadedFile::fake()->image('itemImage.jpg');

        $response = $this->post('/api/upload-images', [
            'title' => 'A new item',
            'description' => 'Dropzone image is required',
            'price' => 60,
            'image' => [$this->file],
            'category_id' => $this->category->id,
            'user_id' => $this->user->id
        ])->assertStatus(201);

        Storage::disk('public')->assertExists('uploadedImages/' . $this->file->hashName());

        $this->assertCount(1, Item::all());
        $this->assertCount(1, Image::all());

        $this->item = Item::first();
        $this->image = Image::first();
    }

    /** @test */
    //actingAs is another way to login if you don't want pass the token
    public function auth_user_can_fetch_all_items()
    {
        factory(Item::class)->create(['user_id' => $this->user->id, 'category_id' => $this->category->id]);

        $items = Item::all();
        $item1 = $items[0];
        $item2 = $items[1];

        $response = $this->get('/api/items');

        //Unlike PostTest, we cannot use $posts->first() and $posts->last() because first and last are for factory and in PostTest we created both posts using factory. Here we are creating 1 post using factory and one is from the setup.
        $response->assertJson([
            'data' => [
                [
                    'id' => $item1->id,
                    'title' => $item1->title,
                    'description' => $item1->description,
                    'price' => $item1->price,
                    'category_id' => $item1->category_id,
                    'user_id' => $item1->user_id,
                    'created_at' => now()->diffForHumans(),

                    //'replies' => [],

                    'bookmarks' => [],

                    'images' => [],

                    'category' => [
                        'id' => $this->category->id,
                        'name' => $this->category->name,
                        'created_at' => $this->category->created_at->diffForHumans()
                    ],

                    'posted_by' => [
                        'id' => $this->user->id,
                        'name' => $this->user->name,
                        'email' => $this->user->email,
                    ],

                    'path' => '/items',
                ],
                [
                    'id' => $item2->id,
                    'title' => $item2->title,
                    'description' => $item2->description,
                    'price' => $item2->price,
                    'category_id' => $item2->category_id,
                    'user_id' => $item2->user_id,
                    'created_at' => now()->diffForHumans(),

                    //'replies' => [],

                    'bookmarks' => [],

                    'images' => [],

                    'category' => [
                        'id' => $this->category->id,
                        'name' => $this->category->name,
                        'created_at' => $this->category->created_at->diffForHumans()
                    ],

                    'posted_by' => [
                        'id' => $this->user->id,
                        'name' => $this->user->name,
                        'email' => $this->user->email,
                    ],

                    'path' => '/items',
                ],
            ],
            'item_count' => 2,
            'links' => [
                'self' => '/posts'
            ]
        ]);
    }

    /** @test */
    public function auth_user_can_create_item_with_images()
    {
        $file1 = UploadedFile::fake()->image('itemImage1.jpg');
        $file2 = UploadedFile::fake()->image('itemImage2.jpg');
        $file3 = UploadedFile::fake()->image('itemImage3.jpg');

        $response = $this->post('/api/upload-images', [
            'title' => 'A new item',
            'description' => 'Dropzone images are required',
            'price' => 60,
            'image' => [$file1, $file2, $file3],
            'category_id' => $this->category->id,
            'user_id' => $this->user->id
        ])->assertStatus(201);

        Storage::disk('public')->assertExists('uploadedImages/' . $file1->hashName());
        Storage::disk('public')->assertExists('uploadedImages/' . $file2->hashName());
        Storage::disk('public')->assertExists('uploadedImages/' . $file3->hashName());

        $this->assertCount(2, Item::all());
        $this->assertCount(4, Image::all());

        $items = Item::all();
        $item = $items[1];

        $images = Image::all();
        $image1 = $images[1];
        $image2 = $images[2];
        $image3 = $images[3];

        $response->assertJson([
            'data' => [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'price' => $item->price,
                'category_id' => $item->category_id,
                'user_id' => $item->user_id,
                'created_at' => now()->diffForHumans(),

                //'replies' => [],

                'bookmarks' => [],

                'images' => [
                    'data' => [
                        [
                            'id' => $image1->id,
                            'path' => $image1->path
                        ],
                        [
                            'id' => $image2->id,
                            'path' => $image2->path
                        ],
                        [
                            'id' => $image3->id,
                            'path' => $image3->path
                        ]
                    ],
                    'image_count' => 3,
                    'links' => [
                        'self' => '/images',
                    ]
                ],

                'category' => [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'created_at' => $this->category->created_at->diffForHumans()
                ],

                'posted_by' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ],

                'path' => '/items',
            ]
        ]);
    }

    /** @test */
    /*public function auth_user_can_update_item_images()
    {

    }*/

    /** @test */
    public function auth_user_can_update_item_details()
    {
        $response = $this->put('/api/items/' . $this->item->id, [
            'title' => 'An updated title',
            'description' => 'This is updated description'
        ]);

        $response->assertStatus(201);

        $this->assertCount(1, Item::all());
        $this->assertCount(1, Image::all());

        $response->assertJson([
            'data' => [
                'id' => $this->item->id,
                'title' => 'An updated title',
                'description' => 'This is updated description',
                'price' => $this->item->price,
                'category_id' => $this->item->category_id,
                'user_id' => $this->item->user_id,
                'created_at' => now()->diffForHumans(),

                //'replies' => [],

                'bookmarks' => [],

                'images' => [
                    'data' => [
                        [
                            'id' => $this->image->id,
                            'path' => $this->image->path
                        ]
                    ],
                    'image_count' => 1,
                    'links' => [
                        'self' => '/images',
                    ]
                ],

                'category' => [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'created_at' => $this->category->created_at->diffForHumans()
                ],

                'posted_by' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ],

                'path' => '/items',
            ]
        ]);
    }

    /** @test */
    //We have to create Item this way because we can not create simple text Item. Dropzone Image is required.
    public function auth_user_can_delete_item()
    {
        $response = $this->delete('/api/items/' . $this->item->id);

        $response->assertStatus(204);

        $this->assertCount(0, Item::all());
        $this->assertCount(0, Image::all());
    }

    /** @test */
    public function auth_user_can_bookmark_an_item()
    {
        $response = $this->post('/api/items/' . $this->item->id . '/bookmark-unbookmark');

        $response->assertStatus(200);

        $this->assertCount(1, $this->user->bookmarks);

        $response->assertJson([
            'data' => [
                [
                    'created_at' => now()->diffForHumans(),
                    'item_id' => $this->item->id,
                    'user_id' => $this->user->id,
                    'path' => '/items/' . $this->item->id,
                ]
            ],
            'bookmark_count' => 1,
            'user_bookmarked' => true,
            'links' => [
                'self' => '/items',
            ],
        ]);
    }

    /** @test */
    public function items_are_returned_with_bookmarks()
    {
        $response = $this->post('/api/items/' . $this->item->id . '/bookmark-unbookmark');

        $response->assertStatus(200);

        $this->assertCount(1, $this->user->bookmarks);

        $response = $this->get('/api/items');

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'id' => $this->item->id,
                    'title' => $this->item->title,
                    'description' => $this->item->description,
                    'price' => $this->item->price,
                    'category_id' => $this->item->category_id,
                    'user_id' => $this->item->user_id,
                    'created_at' => now()->diffForHumans(),

                    //'replies' => [],

                    'bookmarks' => [
                        'data' => [
                            [
                                'created_at' => now()->diffForHumans(),
                                'item_id' => $this->item->id,
                                'user_id' => $this->user->id,
                                'path' => '/items/' . $this->item->id,
                            ]
                        ],
                        'bookmark_count' => 1,
                        'user_bookmarked' => true,
                        'links' => [
                            'self' => '/items',
                        ],
                    ],

                    'images' => [
                        'data' => [
                            [
                                'id' => $this->image->id,
                                'path' => $this->image->path
                            ],
                        ],
                        'image_count' => 1,
                        'links' => [
                            'self' => '/images',
                        ]
                    ],

                    'category' => [
                        'id' => $this->category->id,
                        'name' => $this->category->name,
                        'created_at' => $this->category->created_at->diffForHumans()
                    ],

                    'posted_by' => [
                        'id' => $this->user->id,
                        'name' => $this->user->name,
                        'email' => $this->user->email,
                    ],

                    'path' => '/items'
                ]
            ],
            'item_count' => 1,
            'links' => [
                'self' => '/posts'
            ]
        ]);
    }

    /** @test */
    /*public function auth_user_can_share_item()
    {

    }*/

    /** @test */
    public function auth_user_cannot_create_item_without_images()
    {
        $response = $this->post('/api/upload-images', [
            'title' => 'A new item',
            'description' => 'Dropzone images are required',
            'price' => 60,
            'category_id' => $this->category->id,
            'user_id' => $this->user->id
        ]);

        $response->assertStatus(422);

        $responseString = json_decode($response->getContent(), true); //true will convert the object into array

        $this->assertCount(1, Item::all());
        $this->assertCount(1, Image::all());

        $this->assertArrayHasKey('image', $responseString['errors']['meta']);
    }

    /** @test */
    public function item_details_required_for_an_item_with_Images()
    {
        $response = $this->post('/api/upload-images', [
            'image' => [$this->file],
            'category_id' => $this->category->id,
            'user_id' => $this->user->id
        ]);

        $response->assertStatus(422);

        $this->assertCount(1, Item::all());
        $this->assertCount(1, Image::all());

        $responseString = json_decode($response->getContent(), true); //true will convert the object into array

        $this->assertArrayHasKey(
            'title', $responseString['errors']['meta'],
            'description', $responseString['errors']['meta'],
            'price', $responseString['errors']['meta'],
        );
    }
}
