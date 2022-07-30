<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class DashboardTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_dashboard()
    {
        $user = User::create([
            'name' => 'testUser',
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('password')
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard');

        $response
            ->assertStatus(200)
            ->assertSee('testUser')
            ->assertSee('Dashboard');
    }

    public function test_dashboard_not_logged_in()
    {
        $response = $this->get('/dashboard');

        $response
            ->assertStatus(302)
            ->assertRedirect('/login');
    }
}
