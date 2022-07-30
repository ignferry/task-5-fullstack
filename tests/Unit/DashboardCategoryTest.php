<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class DashboardCategoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    private function userTestData($suffix, bool $isAdmin) 
    {
        return $isAdmin ? [
            'name' => 'testUser' . $suffix,
            'email' => 'testuser' . $suffix . '@gmail.com',
            'password' => bcrypt('password'),
            'type' => 1
        ] 
        : [
            'name' => 'testUser' . $suffix,
            'email' => 'testuser' . $suffix . '@gmail.com',
            'password' => bcrypt('password')
        ];
    }
    
    public function test_index()
    {
        $user = User::create($this->userTestData(1, true));

        for ($i = 1; $i <= 10; $i++) {
            Category::create([
                'name' => 'testCategory' . $i
            ]);
        }
        
        $response = $this
            ->actingAs($user)
            ->get('/dashboard/categories');

        $response->assertStatus(200);

        for ($i = 1; $i <= 10; $i++) {
            $response->assertSee('testCategory' . $i);
        }
    }

    public function test_index_user_cant_access()
    {
        $user = User::create($this->userTestData(1, false));

        Category::create([
            'name' => 'testCategory'
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard/categories');

        $response->assertStatus(403);
    }

    public function test_store()
    {
        $user = User::create($this->userTestData(1, true));

        $response = $this
            ->actingAs($user)
            ->post('/dashboard/categories', [
                'name' => 'testCategory'
            ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/dashboard/categories');
    }

    public function test_update()
    {
        $user = User::create($this->userTestData(1, true));

        Category::create([
            'name' => 'testCategory'
        ]);

        $response = $this
            ->actingAs($user)
            ->put('/dashboard/categories/1', [
                'name' => 'updatedTestCategory'
            ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/dashboard/categories');
    }

    public function test_destroy()
    {
        $user = User::create($this->userTestData(1, true));

        Category::create([
            'name' => 'testCategory'
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/dashboard/categories/1');

        $response
            ->assertStatus(302)
            ->assertRedirect('/dashboard/categories');
    }
}
