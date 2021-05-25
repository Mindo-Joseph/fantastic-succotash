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
                                                <a class="action-icon openCategoryModal" dataid="'.$node["id"].'" href="#"> <i class="mdi mdi-square-edit-outline"></i> </a>

                                                <a class="action-icon" dataid="'.$node["id"].'" onclick="'.$askMessage.'" href="'.url("client/category/delete/".$node["id"]).'"> <i class="mdi mdi-delete"></i> </a>

                                               
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
            foreach($tree as $node) {
                $this->htmlData .='<li class="dd-item dd3-item" data-id="'.$node["id"].'">';

                if($from == 'category'){
                    $this->htmlData .='<div class="dd-handle dd3-handle"></div>';
                }
                $this->htmlData .='<div class="dd3-content">'.$node["slug"].'<span class="inner-div text-right">';

                $status = 2; $icon = 'mdi-lock-open-variant';
                $title = 'Block';
                $askMessage = "return confirm('Are you sure? You want to block category.')";
                if($node["status"] == 2){
                    $askMessage = "return confirm('Are you sure? You want to unblock category.')";
                    $status = 1; $icon = 'mdi-lock'; 
                    $title = 'Unblock';
                }

                if($from == 'category'){
                    if($node["is_core"] == 1){
                        $this->htmlData .='<a class="action-icon openCategoryModal" dataid="'.$node["id"].'" is_vendor="0" href="#"> <i class="mdi mdi-square-edit-outline"></i></a><a class="action-icon" dataid="'.$node["id"].'" title="'.$title.'" onclick="'.$askMessage.'" href="'.url("client/category/delete/".$node["id"]).'"> <i class="mdi '.$icon.'"></i></a>';
                    }

                }elseif($from == 'vendor' && $node["is_core"] == 0){
                    $this->htmlData .='<a class="action-icon openCategoryModal" dataid="'.$node["id"].'" is_vendor="1" href="#"> <i class="mdi mdi-square-edit-outline"></i></a>
                        <a class="action-icon" dataid="'.$node["id"].'" onclick="'.$askMessage.'" href="'.url("client/category/delete/".$node["id"]).'" title="'.$title.'"> <i class="mdi '.$icon.'"></i></a>';
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

    /**     * check if cookie already exist     */
    public function userMetaData($userid, $device_type = 'web', $device_token = 'web')
    {
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
                'card_id' => $this->randomData('wallets'),
                'card_qr_code' => $this->randomBarcode('wallets'),
                'meta_field' => '',
            ];

            Wallet::insert($walletData);
        }
        return 1;
    }

    /* Create random and unique client code*/
    public function randomData($table){
        $random_string = substr(md5(microtime()), 0, 6);
        // after creating, check if string is already used

        while(\DB::table($table)->where('card_id', $random_string)->exists()){
            $random_string = substr(md5(microtime()), 0, 6);
        }
        return $random_string;
    }

    public function randomBarcode($table){
        $barCode = substr(md5(microtime()), 0, 14);
        // $number = mt_rand(1000000000, 9999999999);

        while( \DB::table($table)->where('card_qr_code', $barCode)->exists()){
            $barCode = substr(md5(microtime()), 0, 14);
        }
        return $barCode;
    }
}
