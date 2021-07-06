<?php

namespace App\Http\Controllers\Client;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Client\BaseController;

class SocialMediaController extends BaseController
{
    public function index(Request $request){
        $social_media_details = SocialMedia::get();
        return view('backend.socialmedia.index', compact('social_media_details'));
    }
}
