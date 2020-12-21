<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Product\Http\Requests\ProductForm;
use Webkul\Marketplace\Repositories\MpProductRepository as Product;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Inventory\Repositories\InventorySourceRepository as InventorySource;
use Webkul\Marketplace\Repositories\ProductRepository as SellerProduct;
use Webkul\Marketplace\Repositories\SellerRepository as Seller;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;
use Webkul\Product\Models\Product as BaseProduct;

/**
 * Product controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * AttributeFamilyRepository object
     *
     * @var array
     */
    protected $attributeFamily;

    /**
     * CategoryRepository object
     *
     * @var array
     */
    protected $category;

    /**
     * InventorySourceRepository object
     *
     * @var array
     */
    protected $inventorySource;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * Seller Repository object
     *
     * @var array
     */
    protected $sellerProduct;

    /**
     * Base ProductRepository object
     *
     * @var array
     */
    protected $productRepository;

    /**
     * SellerRepository object
     *
     * @var array
     */
    protected $seller;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeFamilyRepository $attributeFamily
     * @param  Webkul\Category\Repositories\CategoryRepository         $category
     * @param  Webkul\Inventory\Repositories\InventorySourceRepository $inventorySource
     * @param  Webkul\Marketplace\Repositories\ProductRepository       $sellerProduct
     * @param  Webkul\Marketplace\Repositories\SellerRepository        $seller
     * @param  Webkul\Product\Repositories\ProductRepository           $productRepository;
     * @return void
     */
    public function __construct(
        Product $product,
        AttributeFamily $attributeFamily,
        Category $category,
        InventorySource $inventorySource,
        SellerProduct $sellerProduct,
        Seller $seller,
        BaseProductRepository $productRepository
    )
    {
        $this->attributeFamily = $attributeFamily;

        $this->category = $category;

        $this->inventorySource = $inventorySource;

        $this->product = $product;

        $this->sellerProduct = $sellerProduct;

        $this->seller = $seller;

        $this->productRepository = $productRepository;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isSeller = $this->seller->isSeller(auth()->guard('customer')->user()->id);

        if (! $isSeller) {
            return redirect()->route('marketplace.account.seller.create');
        }

        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $families = $this->attributeFamily->all();

        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamily->find($familyId);
        }

        return view($this->_config['view'], compact('families', 'configurableFamily'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if (! request()->get('family') && request()->input('type') == 'configurable' && request()->input('sku') != '') {
            return redirect(url()->current() . '?family=' . request()->input('attribute_family_id') . '&sku=' . request()->input('sku'));
        }

        if (request()->input('type') == 'configurable' && (! request()->has('super_attributes') || !count(request()->get('super_attributes')))) {

            session()->flash('error', 'Please select atleast one configurable attribute.');

            return back();
        }

        $this->validate(request(), [
            'type' => 'required',
            'attribute_family_id' => 'required',
            'sku' => ['required', 'unique:products,sku', new \Webkul\Core\Contracts\Validations\Slug]
        ]);

        $product = $this->product->create(request()->all());

        $sellerProduct = $this->sellerProduct->create([
                'product_id' => $product->id,
                'is_owner' => 1
            ]);

            session()->flash('success', 'Product created successfully.');

        return redirect()->route($this->_config['redirect'], ['id' => $product->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $seller = $this->seller->findOneByField('customer_id', auth()->guard('customer')->user()->id);

        $sellerProduct = $this->sellerProduct->findOneWhere([
                'product_id' => $id,
                'marketplace_seller_id' => $seller->id,
                'is_owner' => 0
            ]);

        if ($sellerProduct) {

            return redirect()->route('marketplace.account.products.edit-assign', ['id' => $sellerProduct->id]);
        }

        $product = $this->product->with(['variants', 'variants.inventories'])->findorFail($id);

        $categories = $this->category->getCategoryTree();

        // $categories = $this->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

        $inventorySources = core()->getCurrentChannel()->inventory_sources;

        return view($this->_config['view'], compact('product', 'categories', 'inventorySources'));
    }

    /**
     * Copy a given Product.
     */
    public function copy(int $productId)
    {
        $originalProduct = $this->productRepository->findOrFail($productId);

        if (! $originalProduct->getTypeInstance()->canBeCopied()) {
            session()->flash('error',
                trans('admin::app.response.product-can-not-be-copied', [
                    'type' => $originalProduct->type,
                ]));

            return redirect()->back();
        }

        if ($originalProduct->parent_id) {
            session()->flash('error',
                trans('admin::app.catalog.products.variant-already-exist-message'));

            return redirect()->back();
        }

        $copiedProduct = $this->sellerProduct->copy($originalProduct);


        if ($copiedProduct instanceof BaseProduct && $copiedProduct->id) {

            $sellerProduct = $this->sellerProduct->create([
                'product_id' => $copiedProduct->id,
                'is_owner' => 1
            ]);

            session()->flash('success', trans('admin::app.response.product-copied'));
        } else {
            session()->flash('error', trans('admin::app.response.error-while-copying'));
        }

        return redirect()->back();
    }

    /**
     * get visible category tree
     *
     * @param integer $id
     * @return mixed
     */
    public function getVisibleCategoryTree($id)
    {
        return $this->category->getModel()->orderBy('position', 'ASC')->where('status', 1)->descendantsOf($id)->toTree();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\Product\Http\Requests\ProductForm $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductForm $request, $id)
    {
        $data = request()->all();

        $this->product->update($data, $id);

        $sellerProduct = $this->sellerProduct->getMarketplaceProductByProduct($id);

        $this->sellerProduct->update(request()->all(), $sellerProduct->id);

        session()->flash('success', 'Product updated successfully.');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sellerProduct = $this->sellerProduct->getMarketplaceProductByProduct($id);

        $this->sellerProduct->delete($sellerProduct->id);

        if ($sellerProduct->is_owner == 1) {
            $this->product->delete($sellerProduct->product_id);
        }

        session()->flash('success', 'Product deleted successfully.');

        return redirect()->back();
    }

    /**
     * Mass Delete the products
     *
     * @return response
     */
    public function massDestroy()
    {
        $productIds = explode(',', request()->input('indexes'));

        foreach ($productIds as $productId) {
            $sellerProduct = $this->sellerProduct->getMarketplaceProductByProduct($productId);

            if ($sellerProduct) {
                if ($sellerProduct->is_owner == 1) {
                    $this->sellerProduct->delete($sellerProduct->id);
                    $this->product->delete($sellerProduct->product_id);
                } else {
                    $this->sellerProduct->delete($sellerProduct->id);
                }
            }
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }
}