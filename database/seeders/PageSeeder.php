<?php

namespace Database\Seeders;
use DB;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::table('pages')->truncate();
        $page_array = ['Privacy Policy', 'Terms & Conditions'];
        foreach ($page_array as $page) {
            Page::create(['title' => $page, 'description' => 'We provide Visitors (as defined below) with access to the Website and Registered Members (as defined below) with access to the Platform subject to the following Terms of Use. By browsing the public areas of the Website, you acknowledge that you have read, understood, and agree to be legally bound by these Terms of Use and our Privacy Policy, which is hereby incorporated by reference (collectively, this “Agreement”). If you do not agree to any of these terms, then please do not use the Website, the App, and/or the Platform. We may change the terms and conditions of these Terms of Use from time to time with or without notice to you.', 'is_published' => 1, 'slug' => Str::slug($page, '-')]);
        }
    }
}
