<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class ApiCategoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    private function createTestCategory(string $suffix) {
        return Category::create([
            'name' => 'testCategory' . $suffix
        ]);
    }

    public function test_list_all()
    {
        $this->createTestCategory(1);
        $this->createTestCategory(2);

        $response = $this->getJson(env('API_URL') . 'categories');

        $response
            ->assertStatus(200)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => 'testCategory1'
                ],
                [
                    'id' => 2,
                    'name' => 'testCategory2'
                ]
            ]);
    }

    public function test_show()
    {
        $this->createTestCategory(1);

        $response = $this->getJson(env('API_URL') . 'categories/1');

        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => 1,
                'name' => 'testCategory1'
            ]);
    }

    public function test_create()
    {
        $user = User::create([
            'name' => 'testUser',
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('password'),
            'type' => 1
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->postJson(env('API_URL') . 'categories', [
                'name' => 'testCategory'
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Category created successfully'
            ]);
    }

    public function test_update()
    {
        $user = User::create([
            'name' => 'testUser',
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('password'),
            'type' => 1
        ]);

        $this->createTestCategory(1);

        $response = $this
            ->actingAs($user, 'api')
            ->putJson(env('API_URL') . 'categories/1', [
                'name' => 'updatedTestCategory'
            ]);

        $response->assertStatus(204);
    }

    public function test_delete()
    {
        $user = User::create([
            'name' => 'testUser',
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('password'),
            'type' => 1
        ]);

        $this->createTestCategory(1);

        $response = $this
            ->actingAs($user, 'api')
            ->deleteJson(env('API_URL') . 'categories/1');

        $response->assertStatus(204);
    }
}
