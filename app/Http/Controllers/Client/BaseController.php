<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Client, Category};

class BaseController extends Controller
{
	private $htmlData = '';
	private $successCount = 0;
	private $makeArray = array();

    public function buildTree($elements, $parentId = 1) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    /*public function printTreeOld($tree, $html = '') {
        
        if(!is_null($tree) && count($tree) > 0) {
            $this->htmlData .='<ol class="dd-list">';
            $askMessage = "return confirm('Are you sure? You want to delete category.')";
            foreach($tree as $node) {
                $this->htmlData .='<li class="dd-item dd3-item" data-id="'.$node["id"].'">
                                    <div class="dd-handle dd3-handle"></div>
                                    <div class="dd3-content">
                                        '.$node["slug"].' 
                                        <span class="inner-div text-right">
                                                <a class="action-icon openCategoryModal" dataid="'.$node["id"].'" href="#"> <h3> <i class="mdi mdi-square-edit-outline"></i> </h3></a>

                                                <a class="action-icon" dataid="'.$node["id"].'" onclick="'.$askMessage.'" href="'.url("client/category/delete/".$node["id"]).'"> <h3> <i class="mdi mdi-delete"></i> </h3></a>

                                               
                                        </span>
                                    </div>';

                if(isset($node['children']) && count($node['children']) > 0){
                    $ss = $this->printTree($node['children']);
                }
                
                $this->htmlData .='</li>';
            }
            $this->htmlData .='</ol>';
        }
        
        return $this->htmlData;
    }*/

    public function printTree($tree, $from = 'category', $html = '') {
        
        if(!is_null($tree) && count($tree) > 0) {
            $this->htmlData .='<ol class="dd-list">';
            $askMessage = "return confirm('Are you sure? You want to delete category.')";
            foreach($tree as $node) {
                $this->htmlData .='<li class="dd-item dd3-item" data-id="'.$node["id"].'">';

                if($from == 'category'){
                    $this->htmlData .='<div class="dd-handle dd3-handle"></div>';
                }
                $this->htmlData .='<div class="dd3-content">'.$node["slug"].'<span class="inner-div text-right">';

                if($from == 'category'){

                    $this->htmlData .='<a class="action-icon openCategoryModal" dataid="'.$node["id"].'" href="#"> <h3> <i class="mdi mdi-square-edit-outline"></i> </h3></a>
                        <a class="action-icon" dataid="'.$node["id"].'" onclick="'.$askMessage.'" href="'.url("client/category/delete/".$node["id"]).'"> <h3> <i class="mdi mdi-delete"></i> </h3></a>';

                }elseif($from == 'vendor' && $node["is_core"] == 0){
                    $this->htmlData .='<a class="action-icon openCategoryModal" dataid="'.$node["id"].'" href="#"> <h3> <i class="mdi mdi-square-edit-outline"></i> </h3></a>
                        <a class="action-icon" dataid="'.$node["id"].'" onclick="'.$askMessage.'" href="'.url("client/category/delete/".$node["id"]).'"> <h3> <i class="mdi mdi-delete"></i> </h3></a>';
                }
                $this->htmlData .='</span> </div>';

                if(isset($node['children']) && count($node['children']) > 0){
                    $ss = $this->printTree($node['children'], $from);
                }
                
                $this->htmlData .='</li>';
            }
            $this->htmlData .='</ol>';
        }
        
        return $this->htmlData;
    }

    function buildArray($elements, $parentId = 1, $count = 0) {
        $branch = array();
        $acCount = $count + 1;

        $did = 0;
        foreach ($elements as $key => $element) {
            if(!empty($element->id)){
                $did = $element->id;
                $branch[$key]['id'] = $element->id;
                $branch[$key]['parent_id'] = $parentId;
                $category = Category::where('id', $element->id)->first();
                $category->parent_id = $parentId;
                $category->position = $key + 1;
                if($category->save()){
                    $this->successCount = $this->successCount + 1;
                }

            }
            
            if (isset($element->children) && !empty($element->children)) {
                $children = $this->buildArray($element->children, $did, $acCount);
                if ($children) {
                    $branch[$key]['child'] = $children;
                }
                
            }
            $count++;
        }

        return $this->successCount;
    }
}
