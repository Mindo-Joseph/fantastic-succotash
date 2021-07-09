<div class="product-wrapper-grid">
    <div class="row margin-res">

      @if(!empty($listData))
        @foreach($listData as $key => $data)
        @if(($data->variant)->isNotEmpty())
        <?php $imagePath = $imagePath2 = '';
        $mediaCount = count($data->media);
        for ($i = 0; $i < $mediaCount && $i < 2; $i++) { 
            if($i == 0){
                $imagePath = $data->media[$i]->image->path['proxy_url'].'300/300'.$data->media[$i]->image->path['image_path'];
            }
            $imagePath2 = $data->media[$i]->image->path['proxy_url'].'300/300'.$data->media[$i]->image->path['image_path'];
        } ?>
        <div class="col-xl-3 col-6 col-grid-box">
            <div class="product-box scale-effect">
                <div class="img-wrapper">
                    <div class="front">
                        <a href="{{route('productDetail', $data->sku)}}"><img class="img-fluid blur-up lazyload" src="{{$imagePath}}" alt=""></a>
                    </div>
                    <div class="cart-info cart-wrap">
                        <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i class="ti-shopping-cart"></i></button> 
                        <a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a>
                        <!-- <a data-toggle="modal" href="#" data-target="#quick-view" title="Quick View"><i class="ti-search" aria-hidden="true"></i></a>
                        <a href="compare.html" title="Compare"><i class="ti-reload" aria-hidden="true"></i></a> -->
                    </div>
                </div>
                <div class="product-detail">
                    <div>
                        <div class="rating">
                        @for($i = 1; $i < 6; $i++)
                            <i class="fa fa-star"></i>
                        @endfor
                    </div>
                    <a href="{{route('productDetail', $data->sku)}}">
                        <h6>{{(!empty($data->translation) && isset($data->translation[0])) ? $data->translation[0]->title : ''}}</h6>
                    </a>
                    <h4>{{Session::get('currencySymbol').($data->variant[0]->price * $data->variant[0]->multiplier)}}</h4>
                    <!-- <ul class="color-variant">
                        <li class="bg-light0"></li>
                        <li class="bg-light1"></li>
                        <li class="bg-light2"></li>
                    </ul> -->
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach
      @endif
    </div>
</div>
<div class="pagination pagination-rounded justify-content-end mb-0">
    {{ $listData->links() }}
</div>