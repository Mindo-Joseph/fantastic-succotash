<?php

namespace Webkul\Marketplace\Repositories;

use DB;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Contracts\Criteria\ActiveProductCriteria;
use Webkul\Product\Contracts\Criteria\AttributeToSelectCriteria;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Models\Product;
use Webkul\Attribute\Models\Attribute;
use Illuminate\Support\Str;
use Exception;

/**
 * Seller Product Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * ProductRepository object
     *
     * @var Object
     */
    protected $productRepository;

    /**
     * ProductInventoryRepository object
     *
     * @var array
     */
    protected $productInventoryRepository;

    /**
     * ProductImageRepository object
     *
     * @var Object
     */
    protected $productImageRepository;

    /**
     * SellerRepository object
     *
     * @var Object
     */
    protected $sellerRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository      $attribute
     * @param  Webkul\Product\Repositories\ProductRepository          $productRepository
     * @param  Webkul\Product\Repositories\ProductInventoryRepository $productInventoryRepository
     * @param  Webkul\Marketplace\Repositories\ProductImageRepository $productImageRepository
     * @param  Webkul\Marketplace\Repositories\SellerRepository       $sellerRepository
     * @param  Illuminate\Container\Container                         $app
     * @return void
     */
    public function __construct(
        AttributeRepository $attribute,
        BaseProductRepository $productRepository,
        ProductInventoryRepository $productInventoryRepository,
        ProductImageRepository $productImageRepository,
        SellerRepository $sellerRepository,
        App $app
    )
    {
        $this->attribute = $attribute;

        $this->productRepository = $productRepository;

        $this->productInventoryRepository = $productInventoryRepository;

        $this->productImageRepository = $productImageRepository;

        $this->sellerRepository = $sellerRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\Product';
    }

    /**
     * @return mixed
     */
    public function create(array $data)
    {
        Event::dispatch('marketplace.catalog.product.create.before');

        $seller = $this->sellerRepository->findOneByField('customer_id', auth()->guard('customer')->user()->id);

        $sellerProduct = parent::create(array_merge($data, [
                'marketplace_seller_id' => $seller->id,
                'is_approved' => core()->getConfigData('marketplace.settings.general.product_approval_required') ? 0 : 1
            ]));

        foreach ($sellerProduct->product->variants as $baseVariant) {
            parent::create([
                    'parent_id' => $sellerProduct->id,
                    'product_id' => $baseVariant->id,
                    'is_owner' => 1,
                    'marketplace_seller_id' => $seller->id,
                    'is_approved' => core()->getConfigData('marketplace.settings.general.product_approval_required') ? 0 : 1
                ]);
        }

        Event::dispatch('marketplace.catalog.product.create.after', $sellerProduct);

        return $sellerProduct;
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        Event::dispatch('marketplace.catalog.product.update.before', $id);

        $sellerProduct = $this->find($id);

        foreach ($sellerProduct->product->variants as $baseVariant) {

            if (! $sellerChildProduct = $this->getMarketplaceProductByProduct($baseVariant->id)) {
                parent::create([
                        'parent_id' => $sellerProduct->id,
                        'product_id' => $baseVariant->id,
                        'is_owner' => 1,
                        'marketplace_seller_id' => $sellerProduct->seller->id,
                        'is_approved' => $sellerProduct->is_approved
                    ]);
            }
        }

        Event::dispatch('marketplace.catalog.product.update.after', $sellerProduct);

        return $sellerProduct;
    }

    /**
     * @return mixed
     */
    public function createAssign(array $data)
    {
        Event::dispatch('marketplace.catalog.assign-product.create.before');

        if (isset($data['seller_id']) && auth()->guard('admin')->user()) {
            $seller = $this->sellerRepository->findOneByField('id', $data['seller_id']);
            unset($data['seller_id']);
        } else if (auth()->guard('customer')->user()) {
            $seller = $this->sellerRepository->findOneByField('customer_id', auth()->guard('customer')->user()->id);
        }

        $sellerProduct = parent::create(array_merge($data, [
                'marketplace_seller_id' => $seller->id,
                'is_approved' => core()->getConfigData('marketplace.settings.general.product_approval_required') ? 0 : 1
            ]));

        if (isset($data['selected_variants'])) {
            foreach ($data['selected_variants'] as $baseVariantId) {
                $sellerChildProduct = parent::create(array_merge($data['variants'][$baseVariantId], [
                        'parent_id' => $sellerProduct->id,
                        'condition' => $sellerProduct->condition,
                        'product_id' => $baseVariantId,
                        'is_owner' => 0,
                        'marketplace_seller_id' => $seller->id,
                        'is_approved' => core()->getConfigData('marketplace.settings.general.product_approval_required') ? 0 : 1
                    ]));

                $this->productInventoryRepository->saveInventories(array_merge($data['variants'][$baseVariantId], [
                        'vendor_id' => $sellerChildProduct->marketplace_seller_id
                    ]), $sellerChildProduct->product);
            }
        }

        $this->productInventoryRepository->saveInventories(array_merge($data, [
                'vendor_id' => $sellerProduct->marketplace_seller_id
            ]), $sellerProduct->product);

        $this->productImageRepository->uploadImages($data, $sellerProduct);

        Event::dispatch('marketplace.catalog.assign-product.create.after', $sellerProduct);

        return $sellerProduct;
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function updateAssign(array $data, $id, $attribute = "id")
    {
        Event::dispatch('marketplace.catalog.assign-product.update.before', $id);

        $sellerProduct = $this->find($id);

        parent::update($data, $id);

        $previousBaseVariantIds = $sellerProduct->variants->pluck('product_id');

        if (isset($data['selected_variants'])) {
            foreach ($data['selected_variants'] as $baseVariantId) {
                $variantData = $data['variants'][$baseVariantId];

                if (is_numeric($index = $previousBaseVariantIds->search($baseVariantId))) {
                    $previousBaseVariantIds->forget($index);
                }

                $sellerChildProduct = $this->findOneWhere([
                        'product_id' => $baseVariantId,
                        'marketplace_seller_id' => $sellerProduct->marketplace_seller_id,
                        'is_owner' => 0
                    ]);

                if ($sellerChildProduct) {
                    parent::update(array_merge($variantData, [
                            'price' => $variantData['price'],
                            'condition' => $sellerProduct->condition
                        ]), $sellerChildProduct->id);

                    $this->productInventoryRepository->saveInventories(array_merge($variantData, [
                            'vendor_id' => $sellerChildProduct->marketplace_seller_id
                        ]), $sellerChildProduct->product);
                } else {
                    $sellerChildProduct = parent::create(array_merge($variantData, [
                            'parent_id' => $sellerProduct->id,
                            'product_id' => $baseVariantId,
                            'condition' => $sellerProduct->condition,
                            'is_approved' => $sellerProduct->id_approved,
                            'is_owner' => 0,
                            'marketplace_seller_id' => $sellerProduct->seller->id,
                        ]));

                    $this->productInventoryRepository->saveInventories(array_merge($variantData, [
                            'vendor_id' => $sellerChildProduct->marketplace_seller_id
                        ]), $sellerChildProduct->product);
                }
            }
        }

        if ($previousBaseVariantIds->count()) {
            $sellerProduct->variants()
                ->whereIn('product_id', $previousVariantIds)
                ->delete();
        }

        $this->productImageRepository->uploadImages($data, $sellerProduct);

        $this->productInventoryRepository->saveInventories(array_merge($data, [
                'vendor_id' => $sellerProduct->marketplace_seller_id
            ]), $sellerProduct->product);

        Event::dispatch('marketplace.catalog.assign-product.update.after', $sellerProduct);

        return $sellerProduct;
    }

    /**
     * @param integer $sellerId
     * @return Collection
     */
    public function getPopularProducts($sellerId, $pageTotal = 4)
    {
        return app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($sellerId) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                $qb = $query->distinct()
                        ->addSelect('product_flat.*')
                        ->leftJoin('marketplace_products', 'product_flat.product_id', '=', 'marketplace_products.product_id')
                        ->where('product_flat.visible_individually', 1)
                        ->where('product_flat.status', 1)
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->whereNotNull('product_flat.url_key')
                        ->where('marketplace_products.marketplace_seller_id', $sellerId)
                        ->where('marketplace_products.is_approved', 1)
                        ->whereIn('product_flat.product_id', $this->getTopSellingProducts($sellerId))
                        ->orderBy('id', 'desc');

                return $qb;
            })->paginate($pageTotal);
    }

    /**
     * Returns top selling products
     *
     * @param integer $sellerId
     * @return mixed
     */
    public function getTopSellingProducts($sellerId)
    {
        $seller = $this->sellerRepository->find($sellerId);

        $result = app('Webkul\Marketplace\Repositories\OrderItemRepository')->getModel()
            ->leftJoin('marketplace_products', 'marketplace_order_items.marketplace_product_id', 'marketplace_products.id')
            ->leftJoin('order_items', 'marketplace_order_items.order_item_id', 'order_items.id')
            ->leftJoin('marketplace_orders', 'marketplace_order_items.marketplace_order_id', 'marketplace_orders.id')
            ->select(DB::raw('SUM(qty_ordered) as total_qty_ordered'), 'marketplace_products.product_id')
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->where('marketplace_products.is_approved', 1)
            ->whereNull('order_items.parent_id')
            ->groupBy('marketplace_products.product_id')
            ->orderBy('total_qty_ordered', 'DESC')
            ->limit(4)
            ->get();

        return $result->pluck('product_id')->toArray();
    }

    /**
     * Returns the total products of the seller
     *
     * @param Seller $seller
     * @return integer
     */
    public function getTotalProducts($seller)
    {
        return $seller->products()->where('is_approved', 1)->where('parent_id', NULL)->count();
    }

    /**
     * Returns the all products of the seller
     *
     * @param integer $seller
     * @return Collection
     */
    public function findAllBySeller($seller)
    {
        $params = request()->input();

        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($seller, $params) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                $qb = $query->distinct()
                        ->addSelect('product_flat.*')
                        ->addSelect(DB::raw('IF( product_flat.special_price_from IS NOT NULL
                            AND product_flat.special_price_to IS NOT NULL , IF( NOW( ) >= product_flat.special_price_from
                            AND NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , IF( product_flat.special_price_from IS NULL , IF( product_flat.special_price_to IS NULL , IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , IF( NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) ) , IF( product_flat.special_price_to IS NULL , IF( NOW( ) >= product_flat.special_price_from, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , product_flat.price ) ) ) AS price1'))

                        ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                        ->leftJoin('marketplace_products', 'product_flat.product_id', '=', 'marketplace_products.product_id')
                        ->where('product_flat.visible_individually', 1)
                        ->where('product_flat.status', 1)
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->whereNotNull('product_flat.url_key')
                        ->where('marketplace_products.marketplace_seller_id', $seller->id)
                        ->where('marketplace_products.is_approved', 1);

                        $qb->addSelect(DB::raw('(CASE WHEN marketplace_products.is_owner = 0 THEN marketplace_products.price ELSE product_flat.price END) AS price2'));

                $queryBuilder = $qb->leftJoin('product_flat as flat_variants', function($qb) use($channel, $locale) {
                    $qb->on('product_flat.id', '=', 'flat_variants.parent_id')
                        ->where('flat_variants.channel', $channel)
                        ->where('flat_variants.locale', $locale);
                });

                if (isset($params['sort'])) {
                    $attribute = $this->attribute->findOneByField('code', $params['sort']);

                    if ($params['sort'] == 'price') {
                        $qb->orderBy($attribute->code, $params['order']);
                    } else {
                        $qb->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $params['order']);
                    }
                }

                $qb = $qb->where(function($query1) {
                    foreach (['product_flat', 'flat_variants'] as $alias) {
                        $query1 = $query1->orWhere(function($query2) use($alias) {
                            $attributes = $this->attribute->getProductDefaultAttributes(array_keys(request()->input()));

                            foreach ($attributes as $attribute) {
                                $column = $alias . '.' . $attribute->code;

                                $queryParams = explode(',', request()->get($attribute->code));

                                if ($attribute->type != 'price') {
                                    $query2 = $query2->where(function($query3) use($column, $queryParams) {
                                        foreach ($queryParams as $filterValue) {
                                            $query3 = $query3->orWhere($column, $filterValue);
                                        }
                                    });
                                } else {
                                    $query2 = $query2->where($column, '>=', core()      ->convertToBasePrice(current($queryParams)))->where($column, '<=',  core()->convertToBasePrice(end($queryParams)));
                                }
                            }
                        });
                    }
                });

                return $qb->groupBy('product_flat.id');
            })->paginate(isset($params['limit']) ? $params['limit'] : 9);

        return $results;
    }

    /**
     * Search Product by Attribute
     *
     * @return Collection
     */
    public function searchProducts($term)
    {
        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($term) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                return $query->distinct()
                        ->addSelect('product_flat.*')
                        ->where('product_flat.status', 1)
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->whereNotNull('product_flat.url_key')
                        ->where('product_flat.name', 'like', '%' . $term . '%')
                        ->orderBy('product_id', 'desc');
            })->paginate(16);

        return $results;
    }

    /**
     * Returns seller by product
     *
     * @param integer $productId
     * @return boolean
     */
    public function getSellerByProductId($productId)
    {
        $product = parent::findOneWhere([
                'product_id' => $productId,
                'is_owner' => 1
            ]);

        if (! $product) {
            return;
        }

        return $product->seller;
    }

    /**
     * Returns count of seller that selling the same product
     *
     * @param Product $product
     * @return integer
     */
    public function getSellerCount($product)
    {
        return $this->scopeQuery(function($query) use($product) {
                return $query
                        // ->leftJoin('marketplace_sellers', 'marketplace_sellers.id', 'marketplace_products.marketplace_seller_id')
                        ->where('marketplace_products.product_id', $product->id)
                        ->where('marketplace_products.is_owner', 0)
                        ->where('marketplace_products.is_approved', 1);
                        // ->where('marketplace_sellers.is_approved', 0);
            })->count();
    }

    /**
     * Returns the seller products of the product
     *
     * @param Product $product
     * @return Collection
     */
    public function getSellerProducts($product)
    {
        return $this->findWhere([
                'product_id' => $product->id,
                'is_owner' => 0,
                'is_approved' => 1
            ]);
    }

    /**
     * Returns the seller products of the product id
     *
     * @param integer $productId
     * @param integer $sellerId
     * @return Collection
     */
    public function getMarketplaceProductByProduct($productId, $sellerId = null)
    {
        if ($sellerId) {
            $seller = $this->sellerRepository->find($sellerId);
        } else {
            if (auth()->guard('customer')->check()) {
                $seller = $this->sellerRepository->findOneByField('customer_id', auth()->guard('customer')->user()->id);
            } else {
                return;
            }
        }

        return $this->findOneWhere([
                'product_id' => $productId,
                // 'is_owner' => 1,
                'marketplace_seller_id' => $seller->id,
            ]);
    }

    /**
     * Copy a product. Is usually called by the copy() function of the ProductController.
     *
     * Always make the copy is inactive so the admin is able to configure it before setting it live.
     *
     * @param int $sourceProductId the id of the product that should be copied
     */
    public function copy(Product $originalProduct): Product
    {
        $this->fillOriginalProduct($originalProduct);

        if (! $originalProduct->getTypeInstance()->canBeCopied()) {
            throw new Exception(trans('admin::app.response.product-can-not-be-copied', ['type' => $originalProduct->type]));
        }

        DB::beginTransaction();

        try {
            $copiedProduct = $this->persistCopiedProduct($originalProduct);

            $this->persistAttributeValues($originalProduct, $copiedProduct);

            $this->persistRelations($originalProduct, $copiedProduct);
        } catch (Exception $e) {
            DB::rollBack();

            report($e);

            throw $e;
        }

        DB::commit();

        return $copiedProduct;
    }

    private function fillOriginalProduct(Product &$sourceProduct): void
    {
        $sourceProduct
            ->load('attribute_family')
            ->load('categories')
            ->load('customer_group_prices')
            ->load('inventories')
            ->load('inventory_sources');
    }

    /**
     * @param $originalProduct
     *
     * @return mixed
     */
    private function persistCopiedProduct($originalProduct): Product
    {
        $copiedProduct = $originalProduct
            ->replicate()
            ->fill([
                // the sku and url_key needs to be unique and should be entered again newly by the admin:
                'sku' => 'temporary-sku-' . substr(md5(microtime()), 0, 6),
            ]);

        $copiedProduct->save();

        return $copiedProduct;
    }

    /**
     * Gather the ids of the necessary product attributes.
     * Throw an Exception if one of these 'basic' attributes are missing for some reason.
     */
    private function gatherAttributeIds(): array
    {
        $ids = [];

        foreach (['name', 'sku', 'status', 'url_key'] as $code) {
            $ids[$code] = Attribute::query()->where(['code' => $code])->firstOrFail()->id;
        }

        return $ids;
    }

    private function persistAttributeValues(Product $originalProduct, Product $copiedProduct): void
    {
        $attributeIds = $this->gatherAttributeIds();

        $newProductFlat = new ProductFlat();

        // only obey copied locale and channel:
        if (isset($originalProduct->product_flats[0])) {
            $newProductFlat = $originalProduct->product_flats[0]->replicate();
        }

        $newProductFlat->product_id = $copiedProduct->id;

        $attributesToSkip = config('products.skipAttributesOnCopy') ?? [];

        $randomSuffix = substr(md5(microtime()), 0, 6);

        foreach ($originalProduct->attribute_values as $oldValue) {
            if (in_array($oldValue->attribute->code, $attributesToSkip)) {
                continue;
            }

            $newValue = $oldValue->replicate();

            // change name of copied product
            if ($oldValue->attribute_id === $attributeIds['name']) {
                $copyOf = trans('admin::app.copy-of');
                $copiedName = sprintf('%s%s (%s)',
                    Str::startsWith($originalProduct->name, $copyOf) ? '' : $copyOf,
                    $originalProduct->name,
                    $randomSuffix
                );
                $newValue->text_value = $copiedName;
                $newProductFlat->name = $copiedName;
            }

            // change url_key of copied product
            if ($oldValue->attribute_id === $attributeIds['url_key']) {
                $copyOfSlug = trans('admin::app.copy-of-slug');
                $copiedSlug = sprintf('%s%s-%s',
                    Str::startsWith($originalProduct->url_key, $copyOfSlug) ? '' : $copyOfSlug,
                    $originalProduct->url_key,
                    $randomSuffix
                );
                $newValue->text_value = $copiedSlug;
                $newProductFlat->url_key = $copiedSlug;
            }

            // change sku of copied product
            if ($oldValue->attribute_id === $attributeIds['sku']) {
                $newValue->text_value = $copiedProduct->sku;
                $newProductFlat->sku = $copiedProduct->sku;
            }

            // force the copied product to be inactive so the admin can adjust it before release
            if ($oldValue->attribute_id === $attributeIds['status']) {
                $newValue->boolean_value = 0;
                $newProductFlat->status = 0;
            }

            $copiedProduct->attribute_values()->save($newValue);

        }

        $newProductFlat->save();
    }

    /**
     * @param $originalProduct
     * @param $copiedProduct
     */
    private function persistRelations($originalProduct, $copiedProduct): void
    {
        $seller = $this->sellerRepository->findOneByField('customer_id', auth()->guard('customer')->user()->id);

        $attributesToSkip = config('products.skipAttributesOnCopy') ?? [];

        if (! in_array('categories', $attributesToSkip)) {
            foreach ($originalProduct->categories as $category) {
                DB::table('product_categories')->insert([
                    'product_id'  => $copiedProduct->id,
                    'category_id' => $category->id,
                ]);
            }
        }

        if (! in_array('inventories', $attributesToSkip)) {
            foreach ($originalProduct->inventories as $inventory) {
                if($inventory->vendor_id == $seller->id) {
                    $copiedProduct->inventories()->save(
                        $inventory->replicate()
                    );
                }

            }
        }

        if (! in_array('customer_group_pricces', $attributesToSkip)) {
            foreach ($originalProduct->customer_group_prices as $customer_group_price) {
                $copiedProduct->customer_group_prices()->save($customer_group_price->replicate());
            }
        }

        if (! in_array('images', $attributesToSkip)) {
            foreach ($originalProduct->images as $image) {
                $copiedProduct->images()->save($image->replicate());
            }
        }

        if (! in_array('super_attributes', $attributesToSkip)) {
            foreach ($originalProduct->super_attributes as $super_attribute) {
                $copiedProduct->super_attributes()->save($super_attribute);
            }
        }

        if (! in_array('bundle_options', $attributesToSkip)) {
            foreach ($originalProduct->bundle_options as $bundle_option) {
                $copiedProduct->bundle_options()->save($bundle_option->replicate());
            }
        }

        if (! in_array('variants', $attributesToSkip)) {
            foreach ($originalProduct->variants as $variant) {
                $variant = $this->copy($variant);
                $variant->parent_id = $copiedProduct->id;
                $variant->save();
            }
        }

        if (config('products.linkProductsOnCopy')) {
            DB::table('product_relations')->insert([
                'parent_id' => $originalProduct->id,
                'child_id'  => $copiedProduct->id,
            ]);
        }
    }
}