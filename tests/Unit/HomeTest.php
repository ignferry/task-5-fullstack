<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\Category;

class HomeTest extends TestCase
{
    private function setUpTestCase() {
        User::create([
            'name' => 'testUser',
            'email' => 'testuse@gmail.com',
            'password' => bcrypt('password'),
        ]);

        Category::create([
            'name' => 'testCategory'
        ]);

        for ($i = 1; $i <= 10; $i++) { 
            Article::create([
                'title' => 'testArticle' . $i,
                'content' => 'Example Content',
                'category_id' => 1,
                'user_id' => 1
            ]);
        }
    }

    public function test_home_articles()
    {
        $this->setUpTestCase();

        $response = $this->get('/');

        $response->assertStatus(200);

        for ($i = 1; $i <= 9; $i++) { 
            $response->assertSee('testArticle' . $i);
        }

        $response->assertDontSee('testArticle10');
    }

    public function test_home_articles_page()
    {
        $this->setUpTestCase();

        $response = $this->get('?page=2');

        $response
            ->assertStatus(200)
            ->assertSee('testArticle10')
            ->assertDontSee('testArticle9');
    }
}


