<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_register()
    {
        $response = $this->postJson(env('API_URL') . 'register', [
            'name' => 'testRegister',
            'email' => 'testregister@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Registration successful'
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'testRegister',
            'email' => 'testregister@gmail.com'
        ]);
    }

    public function test_login()
    {
        User::create([
            'name' => 'testLogin',
            'email' => 'testlogin@gmail.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson(env('API_URL') . 'login', [
            'email' => 'testlogin@gmail.com',
            'password' => 'password'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'message'
            ]);

        $this->assertAuthenticated();
    }
}
