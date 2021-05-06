@extends('layouts.store', ['title' => 'Product'])

@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>

@endsection

@section('content')

<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<style type="text/css">
    .productVariants .firstChild {
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }

    .product-right .color-variant li,
    .productVariants .otherChild {
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }

    .productVariants .otherSize {
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }

    .product-right .size-box ul li.active {
        background-color: inherit;
    }
</style>

<section class="section-b-space">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 collection-filter">
                    <h2>Cart Products</h2>
                </div>
                <div class="col-lg-9 col-sm-12 col-xs-12">
                    <div class="container-fluid">
                        <div class="row">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cart-section section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <table class="table cart-table table-responsive-xs ">
                    <thead>
                        <tr class="table-head">
                            <th scope="col">vendor</th>
                            <th scope="col">image</th>
                            <th scope="col">product name</th>
                            <th scope="col">price</th>
                            <th scope="col">quantity</th>
                            <th scope="col">action</th>
                            <th scope="col">product total</th>
                            <th scope="col">tax</th>
                            <th scope="col">payable amount</th>
                        </tr>
                    </thead>
                    <tbody class="shopping-cart1">
                        <tr>
                            <td>
                                <h4 class="td-color">Mc. Donald's</h4>
                            </td>
                            <td>
                                <a href="#"><img src="{{asset('assets/images/products/product-1.png')}}" alt=""></a>
                            </td>
                            <td>
                                <a href="#">cotton shirt</a>
                            </td>
                            <td>
                                <h2>$63.00</h2>
                            </td>
                            <td>
                                <div class="qty-box">
                                    <div class="input-group">
                                        <input type="number" name="quantity" class="form-control input-number" value="1">
                                    </div>
                                </div>
                            </td>
                            <td><a href="#" class="icon"><i class="ti-close"></i></a></td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4 class="td-color">Mc. Donald's</h4>
                            </td>
                            <td>
                                <a href="#"><img src="{{asset('assets/images/products/product-1.png')}}" alt=""></a>
                            </td>
                            <td>
                                <a href="#">cotton shirt</a>
                            </td>
                            <td>
                                <h2>$63.00</h2>
                            </td>
                            <td>
                                <div class="qty-box">
                                    <div class="input-group">
                                        <input type="number" name="quantity" class="form-control input-number" value="1">
                                    </div>
                                </div>
                            </td>
                            <td><a href="#" class="icon"><i class="ti-close"></i></a></td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3"></td>
                            <td>
                                <h2>Total</h2>
                            </td>
                            <td>
                                <div class="qty-box">
                                    <div class="input-group">
                                        <input type="number" name="quantity" class="form-control input-number" value="1">
                                    </div>
                                </div>
                            </td>
                            <td><a href="#" class="icon"><i class="ti-close"></i></a></td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <h4 class="td-color">KFC's</h4>
                            </td>
                            <td>
                                <a href="#"><img src="{{asset('assets/images/products/product-1.png')}}" alt=""></a>
                            </td>
                            <td>
                                <a href="#">cotton shirt</a>
                            </td>
                            <td>
                                <h2>$63.00</h2>
                            </td>
                            <td>
                                <div class="qty-box">
                                    <div class="input-group">
                                        <input type="number" name="quantity" class="form-control input-number" value="1">
                                    </div>
                                </div>
                            </td>
                            <td><a href="#" class="icon"><i class="ti-close"></i></a></td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4 class="td-color">KFC's</h4>
                            </td>
                            <td>
                                <a href="#"><img src="{{asset('assets/images/products/product-1.png')}}" alt=""></a>
                            </td>
                            <td>
                                <a href="#">cotton shirt</a>
                            </td>
                            <td>
                                <h2>$63.00</h2>
                            </td>
                            <td>
                                <div class="qty-box">
                                    <div class="input-group">
                                        <input type="number" name="quantity" class="form-control input-number" value="1">
                                    </div>
                                </div>
                            </td>
                            <td><a href="#" class="icon"><i class="ti-close"></i></a></td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table cart-table table-responsive-md shopping-cart-footer">
                    <tfoot>
                        <tr>
                            <td>Total :</td>
                            <td>
                                <h2>$6935.00</h2>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="row cart-buttons">
            <div class="col-6"><a href="#" class="btn btn-solid">continue shopping</a></div>
            <div class="col-6"><a class="btn btn-solid checkout" style="color: white;" onMouseOver="this.style.color='black'" onMouseOut="this.style.color='white'">check out</a></div>
        </div>
    </div>
</section>

@endsection

@section('script')


@endsection