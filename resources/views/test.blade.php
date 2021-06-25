@extends('layouts.store', ['title' => 'Product'])
@section('content')
    <div class="container-fluid px-0">
        <div class="row no-gutters">
            <div class="col-12">
                <div class="full-banner custom-space p-right text-end">
                    <img src="{{asset('assets/images/baner.jpg')}}" alt="" class="bg-img blur-up lazyload">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-11">
                                <div class="banner-contain custom-size">
                                    <h2>2018</h2>
                                    <h3>fashion trends</h3>
                                    <h4>special offer</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="wrapper-main mb-5 py-lg-5">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <div class="accordion" id="accordionExample">
                        <div class="card border-0 bg-transparent">
                            <div class="card-header bg-transparent border-0 p-0" id="headingOne">
                            <h2 class="my-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Dairy & Eggs
                                </button>
                            </h2>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body pr-0 pl-2 pb-0 pt-2">
                                <ul class="category-list">
                                    <li><a href="#">Packaged Cheese</a></li>
                                    <li><a href="#">Milk</a></li>
                                    <li><a href="#">Yogurt</a></li>
                                    <li><a href="#">Eggs</a></li>
                                    <li><a href="#">Cream</a></li>
                                    <li><a href="#">Other Creams & Cheeses</a></li>
                                    <li><a href="#">Butter</a></li> 
                                </ul>
                            </div>
                            </div>
                        </div>
                        <div class="card border-0 bg-transparent">
                            <div class="card-header bg-transparent border-0 p-0" id="headingTwo">
                            <h2 class="my-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Bakery
                                </button>
                            </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body pr-0 pl-2 pb-0 pt-2">
                                Some placeholder content for the second accordion panel. This panel is hidden by default.
                            </div>
                            </div>
                        </div>
                        <div class="card border-0 bg-transparent">
                            <div class="card-header bg-transparent border-0 p-0" id="headingThree">
                            <h2 class="my-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Snacks
                                </button>
                            </h2>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                            <div class="card-body pr-0 pl-2 pb-0 pt-2">
                                And lastly, the placeholder content for the third and final accordion panel. This panel is hidden by default.
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/HGutDcpC4i8tMidJ7lIgiVosqPorYZ73GKOrZMU2.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/pYuRTyCq1V0zAdMX5kakYkWKmO81TEkyprg4Cqgp.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/IDGuBlBZ0JaFok1JCLntxzDvDZqBE86Nu28zcCh9.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/WXBAjSXzudaQeoEfXtEaOgVqtCetzGexwmLbWFNX.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/HGutDcpC4i8tMidJ7lIgiVosqPorYZ73GKOrZMU2.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/WXBAjSXzudaQeoEfXtEaOgVqtCetzGexwmLbWFNX.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/HGutDcpC4i8tMidJ7lIgiVosqPorYZ73GKOrZMU2.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/pYuRTyCq1V0zAdMX5kakYkWKmO81TEkyprg4Cqgp.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/HGutDcpC4i8tMidJ7lIgiVosqPorYZ73GKOrZMU2.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/IDGuBlBZ0JaFok1JCLntxzDvDZqBE86Nu28zcCh9.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/HGutDcpC4i8tMidJ7lIgiVosqPorYZ73GKOrZMU2.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/pYuRTyCq1V0zAdMX5kakYkWKmO81TEkyprg4Cqgp.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/IDGuBlBZ0JaFok1JCLntxzDvDZqBE86Nu28zcCh9.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/WXBAjSXzudaQeoEfXtEaOgVqtCetzGexwmLbWFNX.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <a class="card text-center scale-effect" href="#">
                                <div class="product-image p-0 mb-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/HGutDcpC4i8tMidJ7lIgiVosqPorYZ73GKOrZMU2.jpg" alt="">
                                </div>    
                                <div class="media-body align-self-center px-3">
                                    <div class="inner_spacing">
                                        <h3>Pizza</h3>
                                        <p>DeliveryZone</p>
                                        <h4>$ 100</h4>
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) 
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="product-pagination">
                        <div class="theme-paggination-block">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            <li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> <span class="sr-only">Previous</span></a></li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">3</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span> <span class="sr-only">Next</span></a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="product-search-count-bottom">
                                        <h5>Showing Products 1-24 of 10 Result</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> 
  

    
@endsection