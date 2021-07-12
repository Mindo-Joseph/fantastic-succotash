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
            unset($page->primary);
        }
        return $this->successResponse($pages, '', 201);
    }

    public function getPageDetail(Request $request){
        $page_id = $request->page_id;
        $page = Page::select('id', 'slug')->with(['primary' => function($query) use($page_id) {
                    $query->where('is_published', 1)->where('page_id', $page_id);
                }])->firstOrFail();
        $page->title = $page->primary->title;
        $page->description = $page->primary->description;
        unset($page->primary);
        return $this->successResponse($page, '', 201);
    }
}
