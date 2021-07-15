<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{AddonOption, AddonOptionTranslation, AddonSet, AddonSetTranslation, OrderVendorProduct, Banner, Brand, BrandCategory, BrandTranslation, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Category, CategoryHistory, CategoryTranslation, Celebrity, CsvProductImport, CsvVendorImport, LoyaltyCard, Order, OrderProductAddon, OrderProductPrescription, OrderProductRating, OrderProductRatingFile, OrderReturnRequest, OrderReturnRequestFile, OrderTax, OrderVendor, Payment, PaymentOption, Product, ProductAddon, ProductCategory, ProductCelebrity, ProductCrossSell, ProductImage, ProductInquiry, ProductRelated, ProductTranslation, ProductUpSell, ProductVariant, ProductVariantImage, ProductVariantSet, Promocode, PromoCodeDetail, PromocodeRestriction, ServiceArea, SlotDay, SocialMedia, Transaction, User, UserAddress, UserDevice, UserLoyaltyPoint, UserPermissions, UserRefferal, UserVendor, UserWishlist, Variant, VariantCategory, VariantOption, VariantOptionTranslation, VariantTranslation, Vendor, VendorCategory, VendorMedia, VendorOrderStatus, VendorSlot, VendorSlotDate, Wallet};
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ManageContentController extends BaseController
{
    public function deleteAllSoftDeleted()
    {
        try {
            DB::beginTransaction();
            $pro = Product::onlyTrashed()->forceDelete();
            $cat = Category::onlyTrashed()->forceDelete();
            $user = User::where('status', 3)->forceDelete();
            $ven = Vendor::where('status', 2)->forceDelete();
            $banners = Banner::where('status', 2)->forceDelete();
            $variants = Variant::where('status', 2)->forceDelete();
            $promo_codes = Promocode::where('is_deleted', 1)->forceDelete();
            DB::commit();
            return response()->json(['success' => 'Cleaned Successfully']);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function hardDeleteEverything()
    {
        try {
            DB::beginTransaction();
            DB::statement("SET foreign_key_checks=0");
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
            DB::statement("SET foreign_key_checks=1");
            DB::commit();
            return response()->json(['success' => 'Deleted Successfully']);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function importDemoContent()
    {
        try {
            DB::beginTransaction();
            DB::statement("SET foreign_key_checks=0");
            $brands = DB::connection('mysql3')->table('brands')->get();
                Brand::create($brands->toArray());
            $banners = DB::connection('mysql3')->table('banners')->get();
                Banner::create($banners->toArray());
            $vendors = DB::connection('mysql3')->table('vendors')->get();
                Vendor::create($vendors->toArray());
            $slot_day = DB::connection('mysql3')->table('slot_days')->get();
                SlotDay::create($slot_day->toArray());
            $payments = DB::connection('mysql3')->table('payments')->get();
                Payment::create($payments->toArray());
            $variants = DB::connection('mysql3')->table('variants')->get();
                Variant::create($variants->toArray());
            $products = DB::connection('mysql3')->table('products')->get();
                Product::create($products->toArray());
            $addon_sets = DB::connection('mysql3')->table('addon_sets')->get();
                AddonSet::create($addon_sets->toArray());
            $categories = DB::connection('mysql3')->table('categories')->get();
                Category::create($categories->toArray());
            $promocodes = DB::connection('mysql3')->table('promocodes')->get();
                Promocode::create($promocodes->toArray());
            $celebrities = DB::connection('mysql3')->table('celebrities')->get();
                Celebrity::create($celebrities->toArray());
            $vendor_slots = DB::connection('mysql3')->table('vendor_slots')->get();
                VendorSlot::create($vendor_slots->toArray());
            $addon_options = DB::connection('mysql3')->table('addon_options')->get();
                AddonOption::create($addon_options->toArray());
            $loyalty_cards = DB::connection('mysql3')->table('loyalty_cards')->get();
                LoyaltyCard::create($loyalty_cards->toArray());
            $service_areas = DB::connection('mysql3')->table('service_areas')->get();
                ServiceArea::create($service_areas->toArray());
            $vendor_media = DB::connection('mysql3')->table('vendor_media')->get();
                VendorMedia::create($vendor_media->toArray());
            $social_media = DB::connection('mysql3')->table('social_media')->get();
                SocialMedia::create($social_media->toArray());
            $product_addons = DB::connection('mysql3')->table('product_addons')->get();
                ProductAddon::create($product_addons->toArray());
            $product_images = DB::connection('mysql3')->table('product_images')->get();
                ProductImage::create($product_images->toArray());
            $product_up_sells = DB::connection('mysql3')->table('product_up_sells')->get();
                ProductUpSell::create($product_up_sells->toArray());
            $payment_options = DB::connection('mysql3')->table('payment_options')->get();
                PaymentOption::create($payment_options->toArray());
            $variant_options = DB::connection('mysql3')->table('variant_options')->get();
                VariantOption::create($variant_options->toArray());
            $brand_categories = DB::connection('mysql3')->table('brand_categories')->get();
                BrandCategory::create($brand_categories->toArray());
            $vendor_slot_dates = DB::connection('mysql3')->table('vendor_slot_dates')->get();
                VendorSlotDate::create($vendor_slot_dates->toArray());
            $vendor_categories = DB::connection('mysql3')->table('vendor_categories')->get();
                VendorCategory::create($vendor_categories->toArray());
            $product_related = DB::connection('mysql3')->table('product_related')->get();
                ProductRelated::create($product_related->toArray());
            $product_variants = DB::connection('mysql3')->table('product_variants')->get();
                ProductVariant::create($product_variants->toArray());
            $product_inquiries = DB::connection('mysql3')->table('product_inquiries')->get();
                ProductInquiry::create($product_inquiries->toArray());
            $product_categories = DB::connection('mysql3')->table('product_categories')->get();
                ProductCategory::create($product_categories->toArray());
            $promocode_details = DB::connection('mysql3')->table('promocode_details')->get();
                PromoCodeDetail::create($promocode_details->toArray());
            $category_histories = DB::connection('mysql3')->table('category_histories')->get();
                CategoryHistory::create($category_histories->toArray());
            $brand_translations = DB::connection('mysql3')->table('brand_translations')->get();
                BrandTranslation::create($brand_translations->toArray());
            $product_celebrities = DB::connection('mysql3')->table('product_celebrities')->get();
                ProductCelebrity::create($product_celebrities->toArray());
            $product_cross_sells = DB::connection('mysql3')->table('product_cross_sells')->get();
                ProductCrossSell::create($product_cross_sells->toArray());
            $product_variant_sets = DB::connection('mysql3')->table('product_variant_sets')->get();
                ProductVariantSet::create($product_variant_sets->toArray());
            $product_translations = DB::connection('mysql3')->table('product_translations')->get();
                ProductTranslation::create($product_translations->toArray());
            $variant_translations = DB::connection('mysql3')->table('variant_translations')->get();
                VariantTranslation::create($variant_translations->toArray());
            $addon_set_translations = DB::connection('mysql3')->table('addon_set_translations')->get();
                AddonSetTranslation::create($addon_set_translations->toArray());
            $category_translations = DB::connection('mysql3')->table('category_translations')->get();
                CategoryTranslation::create($category_translations->toArray());
            $product_variant_images = DB::connection('mysql3')->table('product_variant_images')->get();
                ProductVariantImage::create($product_variant_images->toArray());
            $promocode_restrictions = DB::connection('mysql3')->table('promocode_restrictions')->get();
                PromocodeRestriction::create($promocode_restrictions->toArray());
            $addon_option_translations = DB::connection('mysql3')->table('addon_option_translations')->get();
                AddonOptionTranslation::create($addon_option_translations->toArray());
            $variant_option_translations = DB::connection('mysql3')->table('variant_option_translations')->get();
                VariantOptionTranslation::create($variant_option_translations->toArray());

            $users = DB::connection('mysql3')->table('users')->where('is_superadmin', 0)->where('is_admin', 0)->get();
            foreach ($users as $user) {
                try {
                    $new_user = new User();
                    $new_user->name = $user->name;
                    $new_user->email = $user->email;
                    $new_user->description = $user->description;
                    $new_user->phone_number = $user->phone_number;
                    $new_user->dial_code = $user->dial_code;
                    $new_user->email_verified_at = $user->email_verified_at;
                    $new_user->password = $user->password;
                    $new_user->type = $user->type;
                    $new_user->status = $user->status;
                    $new_user->country_id = $user->country_id;
                    $new_user->role_id = $user->role_id;
                    $new_user->auth_token = $user->auth_token;
                    $new_user->system_id = $user->system_id;
                    $new_user->remember_token = $user->remember_token;
                    $new_user->facebook_auth_id = $user->facebook_auth_id;
                    $new_user->twitter_auth_id = $user->twitter_auth_id;
                    $new_user->google_auth_id = $user->google_auth_id;
                    $new_user->apple_auth_id = $user->apple_auth_id;
                    $new_user->image = $user->image;
                    $new_user->email_token = $user->email_token;
                    $new_user->email_token_valid_till = $user->email_token;
                    $new_user->phone_token = $user->phone_token;
                    $new_user->phone_token_valid_till = $user->phone_token_valid_till;
                    $new_user->is_email_verified = $user->is_email_verified;
                    $new_user->is_phone_verified = $user->is_phone_verified;
                    $new_user->timezone_id = $user->timezone_id;
                    $new_user->code = $user->code;
                    $new_user->is_superadmin = $user->is_superadmin;
                    $new_user->is_admin = $user->is_admin;
                    $new_user->timezone = $user->timezone;
                    $new_user->save();
                    $wallet = $user->wallet;
                    $user_ids[] = $user->id;

                    $user_device = DB::connection('mysql3')->table('user_devices')->where('user_id', $user->id)->get();
                    if($user_device){
                        $new_user_device = new UserDevice();
                        $new_user_device->user_id = $new_user->id;
                        $new_user_device->device_type = $user_device->device_type;
                        $new_user_device->device_token = $user_device->device_token;
                        $new_user_device->access_token = $user_device->access_token;
                        $new_user_device->save();
                    }
                    $user_vendor = DB::connection('mysql3')->table('user_vendors')->where('user_id', $user->id)->get();
                    if ($user_vendor) {
                        $new_user_vendor = new UserVendor();
                        $new_user_vendor->user_id = $new_user->id;
                        $new_user_vendor->vendor_id = $user_vendor->vendor_id;
                        $new_user_vendor->save();
                    }
                    $user_address = DB::connection('mysql3')->table('user_addresses')->where('user_id', $user_id)->get();
                    if ($user_address) {
                        $new_user_address = new UserAddress();
                        $new_user_address->user_id = $new_user->id;
                        $new_user_address->address = $user_address->address;
                        $new_user_address->street = $user_address->street;
                        $new_user_address->city = $user_address->city;
                        $new_user_address->state = $user_address->state;
                        $new_user_address->latitude = $user_address->latitude;
                        $new_user_address->longitude = $user_address->longitude;
                        $new_user_address->pincode = $user_address->pincode;
                        $new_user_address->is_primary = $user_address->is_primary;
                        $new_user_address->phonecode = $user_address->phonecode;
                        $new_user_address->country_code = $user_address->country_code;
                        $new_user_address->country = $user_address->country;
                        $new_user_address->type = $user_address->type;
                        $new_user_address->save();
                    }
                    $user_refferal = DB::connection('mysql3')->table('user_refferals')->where('user_id', $user->id)->get();
                    if ($user_refferal) {
                        $new_user_refferal = new UserRefferal();
                        $new_user_refferal->refferal_code = $user_refferal->refferal_code;
                        $new_user_refferal->reffered_by = $user_refferal->reffered_by;
                        $new_user_refferal->user_id = $new_user->id;
                        $new_user_refferal->save();
                    }
                    $user_permissions = DB::connection('mysql3')->table('user_permissions')->where('user_id', $user->id)->get();
                    if ($user_permissions) {
                        $new_user_permission = new UserPermissions();
                        $new_user_permission->user_id = $new_user->id;
                        $new_user_permission->permission_id = $user_permissions->permission_id;
                        $new_user_permission->save();
                    }
                } catch (Exception $exception) {
                    // pr($exception->getMessage());
                    // continue;
                }
            }
            DB::statement("SET foreign_key_checks=1");
            DB::commit();
            return response()->json(['success' => 'Imported Successfully']);
        } catch (\PDOException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
