@extends('layouts.store', ['title' => 'Product'])
@section('content')
<div class="container">
<div class="row card-box">
    <div class="col-4 left_box">
        <div class="row">
        <div class="col-12 mb-2">
            <h4 class="page-title">Delivery Address</h4>
        </div>
    </div>
    <div class="row mb-4">
        @forelse($addresses as $address)
        <div class="col-md-12">
            <div class="delivery_box">
                <label class="radio m-0">{{$address->address}}, {{$address->state}} {{$address->pincode}} 
                    <input type="radio" checked="checked" name="is_company">
                    <span class="checkround"></span>
                </label>
            </div>
        </div>
        @empty

        @endforelse
        <div class="col-12 mt-4 text-center">
            <a class="btn btn-solid w-75 m-auto" href="#">
                <i class="fa fa-plus mr-1" aria-hidden="true"></i> Add New Address</a>
        </div>
    </div>
    <div class="col-md-12 mt-4" id="add_new_address_form" style="display:none;">
        <div class="theme-card w-100" id="add_new_address_form">
            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label for="address">Address</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="address" placeholder="Address" aria-label="Recipient's username" aria-describedby="button-addon2">
                        <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="button-addon2">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        </button>
                        </div>
                    </div>
                    <span class="text-danger" id="address_error"></span>
                </div>
            </div>
            <div class="form-row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city" placeholder="City" value="">
                    <span class="text-danger" id="city_error"></span>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="state">State</label>
                    <input type="text" class="form-control" id="state" placeholder="State" value="">
                    <span class="text-danger" id="state_error"></span>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="country">Country</label>
                    <select name="country" id="country" class="form-control" required="">
                    </select>
                    <span class="text-danger" id="country_error"></span>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="pincode">Pincode</label>
                    <input type="text" class="form-control" id="pincode" placeholder="Pincode" value="">
                    <span class="text-danger" id="pincode_error"></span>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="type">Address Type</label>
                    <select name="type" id="type" class="form-control">
                        <option value="1" selected>Home</option>
                        <option value="2">Office</option>
                    </select>
                    <span class="text-danger" id="type_error"></span>
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-12 mt-3">
                    <button type="button" class="btn btn-solid" id="save_address">Save Address</button>
                    <button type="button" class="btn btn-solid black-btn" id="cancel_save_address_btn">Cancel</button>
                </div>
            </div>
        </div>
    </div> 
    </div>
    <div class="col-8">
        <div class="table-responsive">
            <table class="table table-centered table-nowrap table-striped" id="order_table">
                @foreach($cartData->products as $product)
                <tbody>
                    <tr>
                        <td colspan="6">
                            {{$product['vendor']['name']}}
                        </td>
                    </tr>
                    @foreach($product['vendor_products'] as $vendor_product)
                    <tr class="padding-bottom">
                        <td style="width:100px" rowspan="2">
                            <div class="product-img">
                                <img src="{{$vendor_product['pvariant']['media'][0]['image']['path']['proxy_url'].'100/70'.$vendor_product['pvariant']['media'][0]['image']['path']['image_path']}}" alt="">
                            </div>
                        </td>
                        <td class="items-details text-left">
                            <h4>{{$vendor_product['product']['sku']}}</h4>
                            <label><span>Size:</span> Regular</label>
                        </td>
                        <td>
                            <div class="items-price mb-3">${{$vendor_product['pvariant']['price']}}</div>
                        </td>
                        <td>
                            <div class="number">
                                <span class="minus"><i class="fa fa-minus" aria-hidden="true"></i></span>
                                <input style="text-align:center;width:50px;margin:auto;" placeholder="1" type="text" value="{{$vendor_product['quantity']}}">
                                <span class="plus"><i class="fa fa-plus" aria-hidden="true"></i></span>
                            </div>
                            
                        </td>
                        <td class="text-center">
                            <a href="#" class="action-icon d-block mb-3">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </a>
                        </td>
                        <td class="text-right">
                            <div class="items-price mb-3">${{$vendor_product['pvariant']['quantity_price']}}</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="border_0 p-0 border-0">
                            <table class="add_on_items w-100">
                                <thead>
                                    <tr>
                                        <h6 class="m-0 pl-3"><b>Add Ons</b></h6>
                                    </tr>
                                </thead>    
                                <tbody>
                                    <tr class="border_0 padding-top">
                                        <td class="items-details text-left">
                                            <p class="m-0">Spicy Dip</p>
                                        </td>
                                        <td>
                                            <div class="extra-items-price">$5.00</div>
                                        </td>
                                        <td>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="action-icon d-block">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td class="text-right">
                                            <div class="extra-items-price">$5.00</div>
                                        </td>
                                    </tr>
                                    <tr class="border_0 padding-top">
                                        <td class="items-details text-left">
                                            <p class="m-0">Spicy Dip</p>
                                        </td>
                                        <td>
                                            <div class="extra-items-price">$5.00</div>
                                        </td>
                                        <td>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="action-icon d-block">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td class="text-right">
                                            <div class="extra-items-price">$5.00</div>
                                        </td>
                                    </tr>
                                    <tr class="border_0 padding-top">
                                        <td class="items-details text-left">
                                            <p class="m-0">Spicy Dip</p>
                                        </td>
                                        <td>
                                            <div class="extra-items-price">$5.00</div>
                                        </td>
                                        <td>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="action-icon d-block">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td class="text-right">
                                            <div class="extra-items-price">$5.00</div>
                                        </td>
                                    </tr>
                                    <tr class="border_0 padding-top">
                                        <td class="items-details text-left">
                                            <p class="m-0">Spicy Dip</p>
                                        </td>
                                        <td>
                                            <div class="extra-items-price">$5.00</div>
                                        </td>
                                        <td>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="action-icon d-block">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td class="text-right">
                                            <div class="extra-items-price">$5.00</div>
                                        </td>
                                    </tr>
                                    <tr class="border_0 padding-top">
                                        <td class="items-details text-left">
                                            <p class="m-0">Spicy Dip</p>
                                        </td>
                                        <td>
                                            <div class="extra-items-price">$5.00</div>
                                        </td>
                                        <td>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="action-icon d-block">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td class="text-right">
                                            <div class="extra-items-price">$5.00</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @endforeach
                   
                    <tr>
                        <td colspan="2">
                            <div class="coupon_box d-flex align-items-center">
                                <img src="http://local.myorder.com/assets/images/discount_icon.svg" alt="">
                                <input class="form-control" type="text">
                                <button class="btn btn-outline-info">Apply</button>
                            </div>
                        </td> 
                        <td>
                            <label class="d-block txt-13">Delivery Fee</label>
                            <p class="total_amt m-0">Amount</p>
                        </td>
                        <td colspan="3" class="text-right">
                            <label class="d-block  txt-13">$5.00</label>
                            <p class="total_amt m-0">$90.00</p>
                        </td>
                    </tr>
                </tbody>
                @endforeach
                <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td class="pr-0">
                           <p class="mb-1"></p> Sub Total  
                           <p class="mb-1"></p> Wallet 
                           <p class="mb-1"></p> Loyalty (500 pts) 
                           <hr class="my-2">
                           <p class="total_amt m-0">Total Amount</p>
                        </td>
                        <td class="text-right pl-0" colspan="3">
                           <p class="mb-1"></p> $40.00
                           <p class="mb-1"></p> -$60.00
                           <p class="mb-1"></p> -$10.00
                           <hr class="my-2">
                           <p class="total_amt m-0">$100.00</p>
                        </td>
                    </tr>
                    <tr class="border_0">
                        <td colspan="3"></td>
                        <td>Tax</td>
                        <td class="text-right" colspan="2">
                            <p class="m-0"><label class="m-0">CGST 7.5%</label><span class="pl-4">$10.00</span></p>
                            <p class="m-0"><label class="m-0">CGST 7.5%</label><span class="pl-4">$10.00</span></p>
                        </td>
                    </tr>
                    <tr class="border_0">
                        <td colspan="2"></td>
                        <td colspan="2" class="pt-0 pr-0">
                            <hr class="mt-0 mb-2">
                            <p class="total_amt m-0">Amount Payable</p>
                        </td>
                        <td colspan="2" class="pt-0 pl-0 text-right">
                            <hr class="mt-0 mb-2">
                            <p class="total_amt m-0">${{$cartData->total_payable_amount}}</p>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


    <div class="row mb-4">
        <div class="col-lg-3 col-md-4">
            <a class="btn btn-solid" href="#">Continue Shopping</a>
        </div>
        <div class="offset-lg-6 offset-md-4 col-lg-3 col-md-4 text-md-right">
            <a class="btn btn-solid" href="#">Place Order</a>
        </div>
    </div>
</div>
@endsection