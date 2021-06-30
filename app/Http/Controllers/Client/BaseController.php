<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Client, Category, Product, ClientPreference, UserDevice, UserLoyaltyPoint, Wallet};
use Illuminate\Support\Facades\Storage;
use Session;

class BaseController extends Controller
{
	private $htmlData = '';
    private $toggleData = '';
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

    /*      Category tree on vendor col-3 and category  page    */
    public function printTree($tree, $from = 'category', $blockedCategory = [], $html = '') {
        if(!is_null($tree) && count($tree) > 0) {
            $this->htmlData .='<ol class="dd-list">';
            foreach($tree as $node) {
                $this->htmlData .='<li class="dd-item dd3-item" data-id="'.$node["id"].'">';
                if($from == 'category'){
                    $this->htmlData .='<div class="dd-handle dd3-handle"></div>';
                }
                $icon = $node['icon']['proxy_url'].'30/30'.$node['icon']['image_path'];
                if(isset($node['translation_one'])){
                    $this->htmlData .='<div class="dd3-content"><img class="rounded-circle mr-1" src="'.$icon.'">'.$node['translation_one']["name"].'<span class="inner-div text-right">';
                }else{
                    $this->htmlData .='<div class="dd3-content"><img class="rounded-circle mr-1" src="'.$icon.'">'.$node['translation_one']["name"].'<span class="inner-div text-right">';
                }

                if(!in_array($node["id"], $blockedCategory)){
                    $status = 2; //$icon = 'mdi-lock-open-variant';
                    $title = 'Delete'; $icon = 'mdi-delete';
                    $askMessage = "return confirm('Are you sure? You want to delete category.')";
                    if($from == 'category'){
                        if($node["is_core"] == 1){
                            $this->htmlData .='<a class="action-icon openCategoryModal" dataid="'.$node["id"].'" is_vendor="0" href="#"> <i class="mdi mdi-square-edit-outline"></i></a><a class="action-icon" dataid="'.$node["id"].'" title="'.$title.'" onclick="'.$askMessage.'" href="'.url("client/category/delete/".$node["id"]).'"> <i class="mdi '.$icon.'"></i></a>';
                        }

                    }elseif($from == 'vendor' && $node["is_core"] == 0){
                        $this->htmlData .='<a class="action-icon openCategoryModal" dataid="'.$node["id"].'" is_vendor="1" href="#"> <i class="mdi mdi-square-edit-outline"></i></a>
                            <a class="action-icon" dataid="'.$node["id"].'" onclick="'.$askMessage.'" href="'.url("client/category/delete/".$node["id"]).'" title="'.$title.'"> <i class="mdi '.$icon.'"></i></a>';
                    }
                }
                
                $this->htmlData .='</span> </div>';

                if(isset($node['children']) && count($node['children']) > 0){
                    $ss = $this->printTree($node['children'], $from, $blockedCategory);
                }
                $this->htmlData .='</li>';
            }
            $this->htmlData .='</ol>';
        }
        return $this->htmlData;
    }

    /*      Category tree for vendor to enable & disable category      */
    public function printTreeToggle($tree, $activeCategory = []) {
        if(!is_null($tree) && count($tree) > 0) {
            $this->toggleData .='<ol class="dd-list">';
            foreach($tree as $node) {
                // type_id 1 means product in type table
                if($node['type_id'] == 1 || $node['type_id'] == 3){ 
                    $this->toggleData .='<li class="dd-item dd3-item" data-id="'.$node["id"].'">';
                    $icon = $node['icon']['proxy_url'].'30/30'.$node['icon']['image_path'];
                    $this->toggleData .='<div class="dd3-content"><img class="rounded-circle mr-1" src="'.$icon.'">'.$node["slug"].'<span class="inner-div text-right">';
                    $name = 'category['.$node["id"].']';
                    $this->toggleData .='<a class="action-icon" data-id="'.$node["id"].'" href="javascript:void(0)">';
                    if($node['type_id'] == 3){
                        if(in_array($node["id"], $activeCategory) && $node["parent_id"] == 3){
                            $this->toggleData .='<input class="form-control" type="checkbox" data-id="'.$node["id"].'" name="'.$name.'"  data-color="#43bee1" data-plugin="switchery" checked>';
                        }elseif(in_array($node["id"], $activeCategory) && in_array($node["parent_id"], $activeCategory)){
                            $this->toggleData .='<input class="form-control" type="checkbox" data-id="'.$node["id"].'" name="'.$name.'"  data-color="#43bee1" data-plugin="switchery" checked>';
                        }else{
                            $this->toggleData .='<input type="checkbox" data-id="'.$node["id"].'" name="'.$name.'" data-color="#43bee1" class="form-control activeCategory"  data-plugin="switchery">';
                        }
                    }else{
                        if(in_array($node["id"], $activeCategory) && $node["parent_id"] == 1){
                            $this->toggleData .='<input class="form-control" type="checkbox" data-id="'.$node["id"].'" name="'.$name.'"  data-color="#43bee1" data-plugin="switchery" checked>';
                        }elseif(in_array($node["id"], $activeCategory) && in_array($node["parent_id"], $activeCategory)){
                            $this->toggleData .='<input class="form-control" type="checkbox" data-id="'.$node["id"].'" name="'.$name.'"  data-color="#43bee1" data-plugin="switchery" checked>';
                        }else{
                            $this->toggleData .='<input type="checkbox" data-id="'.$node["id"].'" name="'.$name.'" data-color="#43bee1" class="form-control activeCategory"  data-plugin="switchery">';
                        }
                    }
                    $this->toggleData .='<input type="hidden" name="category_id[]" value="'.$node["id"].'">';
                    $this->toggleData .='</a></span> </div>';
                    if(isset($node['children']) && count($node['children']) > 0){
                        $ss = $this->printTreeToggle($node['children'], $activeCategory);
                    }
                    $this->toggleData .='</li>';
                }
            }
            $this->toggleData .='</ol>';
        }
        return $this->toggleData;
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
    
    public function userMetaData($userid, $device_type = 'web', $device_token = 'web'){
        $device = UserDevice::where('user_id', $userid)->first();
        if(!$device){
            $user_device[] = [
                'user_id' => $userid,
                'device_type' => $device_type,
                'device_token' => $device_token,
                'access_token' => ''
            ];

            UserDevice::insert($user_device);
        }

        $loyaltyPoints = UserLoyaltyPoint::where('user_id', $userid)->first();
        if(!$loyaltyPoints){
            $loyalty[] = [
                'user_id' => $userid,
                'points' => 0
            ];
            UserLoyaltyPoint::insert($loyalty);
        }
        $wallet = Wallet::where('user_id', $userid)->first();
        if(!$wallet){
            $walletData[] = [
                'user_id' => $userid,
                'type' => 1,
                'balance' => 0,
                'card_id' => $this->randomData('wallets', 6, 'card_id'),
                'card_qr_code' => $this->randomBarcode('wallets'),
                'meta_field' => '',
            ];
            Wallet::insert($walletData);
        }
        return 1;
    }

    /* Create random and unique client code*/
    public function randomData($table, $digit, $where){
        $random_string = substr(md5(microtime()), 0, $digit);
        // after creating, check if string is already used
        while(\DB::table($table)->where($where, $random_string)->exists()){
            $random_string = substr(md5(microtime()), 0, $digit);
        }
        return $random_string;
    }

    public function randomBarcode($table){
        $barCode = substr(md5(microtime()), 0, 14);
        while( \DB::table($table)->where('card_qr_code', $barCode)->exists()){
            $barCode = substr(md5(microtime()), 0, 14);
        }
        return $barCode;
    }
}
