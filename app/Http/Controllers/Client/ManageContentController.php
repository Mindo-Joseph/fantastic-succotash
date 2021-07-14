<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{AddonOption, AddonOptionTranslation, AddonSet, AddonSetTranslation, OrderVendorProduct, Banner, Brand, BrandCategory, BrandTranslation, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Category, CategoryHistory, CategoryTranslation, Celebrity, CsvProductImport, CsvVendorImport, LoyaltyCard, Order, OrderProductAddon, OrderProductPrescription, OrderProductRating, OrderProductRatingFile, OrderReturnRequest, OrderReturnRequestFile, OrderTax, OrderVendor, Payment, PaymentOption, Product, ProductAddon, ProductCategory, ProductCelebrity, ProductCrossSell, ProductImage, ProductInquiry, ProductRelated, ProductTranslation, ProductUpSell, ProductVariant, ProductVariantImage, ProductVariantSet, Promocode, PromoCodeDetail, PromocodeRestriction, ServiceArea, SlotDay, SocialMedia, Transaction, User, UserAddress, UserDevice, UserLoyaltyPoint, UserPermissions, UserRefferal, UserVendor, UserWishlist, Variant, VariantCategory, VariantOption, VariantOptionTranslation, VariantTranslation, Vendor, VendorCategory, VendorMedia, VendorOrderStatus, VendorSlot, VendorSlotDate, Wallet};
use Exception;

class ManageContentController extends BaseController
{
    public function deleteAllSoftDeleted()
    {
        try {
            \DB::beginTransaction();
            $pro = Product::onlyTrashed()->forceDelete();
            $cat = Category::onlyTrashed()->forceDelete();
            $user = User::where('status', 3)->forceDelete();
            $ven = Vendor::where('status', 2)->forceDelete();
            $banners = Banner::where('status', 2)->forceDelete();
            $variants = Variant::where('status', 2)->forceDelete();
            $promo_codes = Promocode::where('is_deleted', 1)->forceDelete();
            \DB::commit();
            return response()->json(['success' => 'Cleaned Successfully']);
        } catch (\PDOException $e) {
            \DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function hardDeleteEverything()
    {
        try {
            \DB::beginTransaction();
            \DB::statement("SET foreign_key_checks=0");
            Cart::truncate();
            Brand::truncate();
            Order::truncate();
            Banner::truncate();
            Vendor::truncate();
            SlotDay::truncate();
            Payment::truncate();
            Variant::truncate();
            Product::truncate();
            AddonSet::truncate();
            Category::truncate();
            OrderTax::truncate();
            Promocode::truncate();
            CartAddon::truncate();
            Celebrity::truncate();
            VendorSlot::truncate();
            CartCoupon::truncate();
            AddonOption::truncate();
            LoyaltyCard::truncate();
            ServiceArea::truncate();
            VendorMedia::truncate();
            CartProduct::truncate();
            SocialMedia::truncate();
            Transaction::truncate();
            OrderVendor::truncate();
            ProductAddon::truncate();
            ProductImage::truncate();
            ProductUpSell::truncate();
            PaymentOption::truncate();
            VariantOption::truncate();
            BrandCategory::truncate();
            VendorSlotDate::truncate();
            VendorCategory::truncate();
            ProductRelated::truncate();
            ProductVariant::truncate();
            ProductInquiry::truncate();
            ProductCategory::truncate();
            CsvVendorImport::truncate();
            VariantCategory::truncate();
            PromoCodeDetail::truncate();
            CategoryHistory::truncate();
            CsvProductImport::truncate();
            BrandTranslation::truncate();
            ProductCelebrity::truncate();
            ProductCrossSell::truncate();
            ProductVariantSet::truncate();
            VendorOrderStatus::truncate();
            OrderProductAddon::truncate();
            OrderProductRating::truncate();
            ProductTranslation::truncate();
            VariantTranslation::truncate();
            OrderVendorProduct::truncate();
            OrderReturnRequest::truncate();
            AddonSetTranslation::truncate();
            CategoryTranslation::truncate();
            ProductVariantImage::truncate();
            PromocodeRestriction::truncate();
            AddonOptionTranslation::truncate();
            OrderProductRatingFile::truncate();
            OrderReturnRequestFile::truncate();
            CartProductPrescription::truncate();
            CartProductPrescription::truncate();
            VariantOptionTranslation::truncate();
            OrderProductPrescription::truncate();
            $users = User::where('is_superadmin', 0)->where('is_admin', 0)->get();
            foreach ($users as $user) {
                Wallet::where('holder_id', $user->id)->forceDelete();
                UserDevice::where('user_id', $user->id)->forceDelete();
                UserVendor::where('user_id', $user->id)->forceDelete();
                UserAddress::where('user_id', $user->id)->forceDelete();
                UserWishlist::where('user_id', $user->id)->forceDelete();
                UserRefferal::where('user_id', $user->id)->forceDelete();
                UserPermissions::where('user_id', $user->id)->forceDelete();
                UserLoyaltyPoint::where('user_id', $user->id)->forceDelete();
                $user->forceDelete();
            }
            \DB::statement("SET foreign_key_checks=1");
            \DB::commit();
            return response()->json(['success' => 'Deleted Successfully']);
        } catch (\PDOException $e) {
            \DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function importDemoContent()
    {
        // dd("here mine mine Mine Mine");
        try {
            \DB::beginTransaction();
            $users = \DB::connection('mysql3')->table('users')->where('is_superadmin', 0)->where('is_admin', 0)->get();
            foreach ($users as $user) {
                try{
                    User::insert([
                        'name' => $user->name,
                        'type' => $user->type,
                        'code' => $user->code,
                        'email' => $user->email,
                        'image' => $user->image,
                        'status' => $user->status,
                        'role_id' => $user->role_id,
                        'is_admin' => $user->is_admin,
                        'password' => $user->password,
                        'timezone' => $user->timezone,
                        'password' => $user->password,
                        'dial_code' => $user->dial_code,
                        'system_id' => $user->system_id,
                        'country_id' => $user->country_id,
                        'auth_token' => $user->auth_token,
                        'updated_at' => $user->updated_at,
                        'created_at' => $user->created_at,
                        'description' => $user->description,
                        'timezone_id' => $user->timezone_id,
                        'phone_token' => $user->phone_token,
                        'email_token' => $user->email_token,
                        'phone_number' => $user->phone_number,
                        'apple_auth_id' => $user->apple_auth_id,
                        'is_superadmin' => $user->is_superadmin,
                        'google_auth_id' => $user->google_auth_id,
                        'remember_token' => $user->remember_token,
                        'twitter_auth_id' => $user->twitter_auth_id,
                        'facebook_auth_id' => $user->facebook_auth_id,
                        'email_verified_at' => $user->email_verified_at,
                        'is_email_verified' => $user->is_email_verified,
                        'is_phone_verified' => $user->is_phone_verified,
                        'email_token_valid_till' => $user->email_token_valid_till,
                        'phone_token_valid_till' => $user->phone_token_valid_till
                    ]);
                }
                catch(Exception $exception){
                    continue;
                }
            }
            \DB::commit();
            return response()->json(['success' => 'Imported Successfully']);
        } catch (\PDOException $e) {
            \DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
