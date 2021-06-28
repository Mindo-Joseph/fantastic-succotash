<?php

namespace Database\Seeders;
use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Database\Seeder;

class UpdateCategorySlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();
        foreach ($categories as  $category) {
            $category->slug = Str::slug($category->slug, "-");;
            $category->save();
        }
    }
}
