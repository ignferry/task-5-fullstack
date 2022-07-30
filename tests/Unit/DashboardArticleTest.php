<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use phpDocumentor\Reflection\Types\Boolean;

class DashboardArticleTest extends TestCase
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

    public function test_index_user()
    {
        $user = User::create($this->userTestData(1, false));
        User::create($this->userTestData(2, false));

        Category::create([
            'name' => 'testCategory'
        ]);

        Article::create([
            'title' => 'testArticle1',
            'content' => 'Example Content',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Article::create([
            'title' => 'testArticle2',
            'content' => 'Example Content',
            'category_id' => 1,
            'user_id' => 2
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard/articles');

        $response
            ->assertStatus(200)
            ->assertSee('testArticle1')
            ->assertDontSee('testArticle2');
    }

    public function test_index_admin()
    {
        $user = User::create($this->userTestData(1, true));
        User::create($this->userTestData(2, false));

        Category::create([
            'name' => 'testCategory'
        ]);

        Article::create([
            'title' => 'testArticle1',
            'content' => 'Example Content',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Article::create([
            'title' => 'testArticle2',
            'content' => 'Example Content',
            'category_id' => 1,
            'user_id' => 2
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard/articles');

        $response
            ->assertStatus(200)
            ->assertSee('testArticle1')
            ->assertSee('testArticle2');
    }

    public function test_index_page()
    {
        $user = User::create($this->userTestData(1, false));

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

        $response = $this
            ->actingAs($user)
            ->get('/dashboard/articles?page=2');

        $response
            ->assertStatus(200)
            ->assertSee('testArticle10')
            ->assertDontSee('testArticle9');
    }

    public function test_create()
    {
        $user = User::create($this->userTestData(1, false));

        Category::create([
            'name' => 'testCategory'
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard/articles/create');

        $response 
            ->assertStatus(200)
            ->assertSee('Create New Article');
    }

    public function test_store()
    {
        $user = User::create($this->userTestData(1, false));

        Category::create([
            'name' => 'testCategory'
        ]);

        $image = UploadedFile::fake()->image('testImage.jpg');

        $response = $this
            ->actingAs($user)
            ->post('/dashboard/articles', [
                'title' => 'testArticle',
                'content' => 'Example Content',
                'image' => $image,
                'category_id' => 1,
                'user_id' => 1
            ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/dashboard/articles');
    }

    public function test_show()
    {
        $user = User::create($this->userTestData(1, false));

        Category::create([
            'name' => 'testCategory'
        ]);

        Article::create([
            'title' => 'testArticle1',
            'content' => 'Example Content',
            'category_id' => 1,
            'user_id' => 1
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard/articles/1');

        $response
            ->assertStatus(200)
            ->assertSee('testArticle1')
            ->assertSee('Example Content');
    }

    public function test_edit()
    {
        $user = User::create($this->userTestData(1, false));

        Category::create([
            'name' => 'testCategory'
        ]);

        Article::create([
            'title' => 'testArticle1',
            'content' => 'Example Content',
            'category_id' => 1,
            'user_id' => 1
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard/articles/1/edit');

        $response
            ->assertStatus(200)
            ->assertSee('Edit Article');
    }

    public function test_update()
    {
        $user = User::create($this->userTestData(1, false));

        Category::create([
            'name' => 'testCategory'
        ]);

        Article::create([
            'title' => 'testArticle1',
            'content' => 'Example Content',
            'category_id' => 1,
            'user_id' => 1
        ]);

        $image = UploadedFile::fake()->image('testImage.jpg');

        $response = $this
            ->actingAs($user)
            ->put('/dashboard/articles/1', [
                'title' => 'updatedTestArticle',
                'content' => 'Updated Example Content',
                'image' => $image,
                'category_id' => 1,
                'user_id' => 1
            ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/dashboard/articles');
    }

    public function test_destroy()
    {
        $user = User::create($this->userTestData(1, false));

        Category::create([
            'name' => 'testCategory'
        ]);

        Article::create([
            'title' => 'testArticle1',
            'content' => 'Example Content',
            'category_id' => 1,
            'user_id' => 1
        ]);

        $response = $this 
            ->actingAs($user)
            ->delete('/dashboard/articles/1');

        $response
            ->assertStatus(302)
            ->assertRedirect('/dashboard/articles');
    }
}
