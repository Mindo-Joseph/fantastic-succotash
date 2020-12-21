<?php
    $productInventory = app('Webkul\Product\Repositories\ProductInventoryRepository')->findOneByField(['vendor_id' => 0, 'product_id' => $product->id]);
?>

{!! view_render_event('bagisto.shop.products.view.stock.before', ['product' => $product]) !!}

<div class="col-12 availability">
    @if(isset($productInventory->qty))
        <button
            type="button"
            class="{{! $productInventory->qty ? '' : 'active' }} disable-box-shadow">
            {{ $productInventory->qty ? __('shop::app.products.in-stock') : __('shop::app.products.out-of-stock') }}
        </button>
    @else
        <button
            type="button"
            class="{{! $product->haveSufficientQuantity(1) ? '' : 'active' }} disable-box-shadow">
            {{ $product->haveSufficientQuantity(1) ? __('shop::app.products.in-stock') : __('shop::app.products.out-of-stock') }}
        </button>
    @endif
</div>

{!! view_render_event('bagisto.shop.products.view.stock.after', ['product' => $product]) !!}