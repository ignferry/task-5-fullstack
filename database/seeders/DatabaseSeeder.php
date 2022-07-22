<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'Programming'
        ]);

        Category::create([
            'name' => 'Cooking'
        ]);

        Category::create([
            'name' => 'Sports'
        ]);

        User::factory(5)->create();

        Article::factory(20)->create();
    }
}
