<?php

namespace App\Http\Controllers\Api\v1;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;

class CMSPageController extends Controller{

    use ApiResponser;

    public function getPageList(){
        $pages = Page::select('id', 'slug')->with(array('primary' => function($query) {
                    $query->where('is_published', 1);
                    }))->latest('id')->get();
        foreach ($pages as $page) {
            $page->title = $page->primary->title;
            $page->url = asset('extra-page/'.$page->slug);
            unset($page->primary);
        }
        return $this->successResponse($pages, '', 201);
    }
    public function getPageDetail(Request $request){
        $pages = Page::select('id', 'slug')->with(['primary' => function($query) {
                    $query->where('is_published', 1)->where('page_id', $request->page_id);
                }])->latest('id')->first();
        foreach ($pages as $page) {
            $page->title = $page->primary->title;
            $page->description = $page->primary->description;
            unset($page->primary);
        }
        return $this->successResponse($pages, '', 201);
    }
}
