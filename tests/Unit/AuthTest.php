<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_register_page()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_register_submit()
    {
        $response = $this->post('/register', [
            'name' => 'testUser',
            'email' => 'testuser@gmail.com',
            'password'=> 'password',
            'password_confirmation' => 'password'
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/dashboard');
    }

    public function test_login_submit()
    {
        User::create([
            'name' => 'testUser',
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/login', [
            'email' => 'testuser@gmail.com',
            'password' => 'password'
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/dashboard')
            ->assertCookie('token');
    }

    public function test_logout()
    {
        $user = User::create([
            'name' => 'testUser',
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('password')
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/logout');

        $response
            ->assertStatus(302)
            ->assertRedirect('/');
    }
}
