<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\UploadedFile;

class ApiArticleTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    private function createTestArticle() {
        return Article::create([
            'title' => 'testArticle',
            'category_id' => 1,
            'content' => '<p>Example content</p>',
            'user_id' => 1
        ]);
    }

    private function createTestCategory() {
        return Category::create([
            'name' => 'testCategory'
        ]);
    }

    public function test_list_all()
    {
        $response = $this->getJson(env('API_URL') . 'articles');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links',
                    'path',
                    'per_page',
                    'to',
                    'total'
                ]
            ]);
    }

    public function test_show()
    {
        $user = User::create([
            'name' => 'testUser',
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('password')
        ]);

        $category = $this->createTestCategory();
        $article = $this->createTestArticle();

        $response = $this->getJson(env('API_URL') . 'articles/1');

        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => 1,
                'title' => 'testArticle',
                'content' => '<p>Example content</p>',
                'image' => null,
                'user' => [
                    'id' => 1,
                    'name' => 'testUser',
                    'email' => 'testuser@gmail.com'
                ],
                'category' => [
                    'id' => 1,
                    'name' => 'testCategory'
                ]
            ]);
    }

    public function test_create()
    {
        $user = User::create([
            'name' => 'testUser',
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('password')
        ]);

        $category = $this->createTestCategory();

        $image = UploadedFile::fake()->image('testImage.jpg');

        $response = $this
            ->actingAs($user, 'api')
            ->postJson(env('API_URL') . 'articles', [
                'title' => 'testArticle',
                'content' => '<p>Example content</p>',
                'image' => $image,
                'category_id' => 1,
                'user_id' => 1
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Article created successfully'
            ]);

        $this->assertDatabaseHas('articles', [
            'title' => 'testArticle',
            'content' => '<p>Example content</p>',
            'category_id' => 1,
            'user_id' => 1
        ]);
    }
    
    public function test_update()
    {
        $user = User::create([
            'name' => 'testUser',
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('password')
        ]);
        
        $category = $this->createTestCategory();
        $article = $this->createTestArticle();

        $image = UploadedFile::fake()->image('testImage.jpg');

        $response = $this
            ->actingAs($user, 'api')
            ->putJson(env('API_URL') . 'articles/1', [
                'title' => 'testArticleUpdated',
                'content' => '<p>Updated example content</p>',
                'image' => $image,
                'category_id' => 1,
                'user_id' => 1,
                '_method' => 'PUT'
            ]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('articles',[
            'title' => 'testArticle',
            'content' => '<p>Example content</p>'
        ]);

        $this->assertDatabaseHas('articles', [
            'title' => 'testArticleUpdated',
            'content' => '<p>Updated example content</p>',
            'category_id' => 1,
            'user_id' => 1,
        ]);
    }
    public function test_delete()
    {
        $user = User::create([
            'name' => 'testUser',
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('password')
        ]);

        $category = $this->createTestCategory();
        $article = $this->createTestArticle();

        $response = $this
            ->actingAs($user, 'api')
            ->deleteJson(env('API_URL') . 'articles/1');

        $response->assertStatus(204);

        $this->assertDatabaseMissing('articles', [
            'title' => 'testArticle',
            'content' => '<p>Example content</p>',
            'category_id' => 1,
            'user_id' => 1
        ]);
    }
}
