<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CatControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin_user;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an admin user and authenticate
        $this->admin_user = User::factory()->create([
            'is_admin' => true
        ]);
        
        $this->actingAs($this->admin_user, 'sanctum');
    }

    /** @test */
    public function it_cat_create_a_cat()
    {
        $response = $this->postJson('/api/cats', [
            'name' => 'Fluffy',
            'age' => 2,
            'breed' => 'Siamese',
            'description' => 'A fluffy cat',
            'image' => 'https://example.com/fluffy.jpg',
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'name' => 'Fluffy',
            'age' => 2,
            'breed' => 'Siamese',
            'description' => 'A fluffy cat',
            'image' => 'https://example.com/fluffy.jpg',
        ]);

        $this->assertDatabaseHas('cats', [
            'name' => 'Fluffy',
            'age' => 2,
            'breed' => 'Siamese',
            'description' => 'A fluffy cat',
            'image' => 'https://example.com/fluffy.jpg',
        ]);
    }

    /** @test */
    public function it_can_list_all_cats()
    {
        Cat::factory()->count(3)->create();

        $response = $this->getJson('/api/cats');

        $response->assertStatus(200);

        $response->assertJsonCount(3);
    }

    /** @test */
    public function it_can_show_a_cat()
    {
        $cat = Cat::factory()->create();

        $response = $this->getJson('/api/cats/' . $cat->id);

        $response->assertStatus(200);

        $response->assertJson([
            'name' => $cat->name,
            'age' => $cat->age,
            'breed' => $cat->breed,
            'description' => $cat->description,
            'image' => $cat->image,
        ]);
    }

    /** @test */
    public function it_can_update_a_cat()
    {
        $cat = Cat::factory()->create();

        $response = $this->putJson('/api/cats/' . $cat->id, [
            'name' => 'Fluffy',
            'age' => 2,
            'breed' => 'Siamese',
            'description' => 'A fluffy cat',
            'image' => 'https://example.com/fluffy.jpg',
            'adopted' => true,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cats', [
            'name' => 'Fluffy',
            'age' => 2,
            'breed' => 'Siamese',
            'description' => 'A fluffy cat',
            'image' => 'https://example.com/fluffy.jpg',
            'adopted' => true,
        ]);
    }

    /** @test */
    public function it_can_delete_a_cat()
    {
        $cat = Cat::factory()->create();

        $response = $this->deleteJson('/api/cats/' . $cat->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('cats', [
            'id' => $cat->id,
        ]);
    }
}
