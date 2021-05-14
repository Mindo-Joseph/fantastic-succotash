$(document).ready(function() {
    function cartHeader() {
        $(".shopping-cart").html(" ");
        
        $.ajax({
            data: '',
            type: "get",
            dataType: 'json',
            url: cart_product_url,
            success: function(data) {
                if (data.res == "null") {
                    $(".shopping-cart").html(data.html);
                } else {
                    var products = data.products;
                    for (i = 0; i < products.length; i++) {
                        var vendor_products = products[i].vendor_products;
                        for (j = 0; j < vendor_products.length; j=j+2) {
                        $(".shopping-cart").append("<li id='cart_product_"+vendor_products[j].id+"'><div class='media'><a href='#'><img alt='' class='mr-3' src='" + vendor_products[j].pvariant.media[0].image.path.proxy_url + '200/200' + vendor_products[j].pvariant.media[0].image.path.image_path + "'></a><div class='media-body'><a href='#'><h4>" + vendor_products[j].product.sku + "</h4></a><h4><span>" + vendor_products[j].quantity + " x $" + products[i].payable_amount + "</span></h4></div></div><div class='close-circle'><a href='#' data-product="+vendor_products[j].id+" class='remove-product'><i class='fa fa-times' aria-hidden='true'></i></a></div></li>");
                        }
                    }
                    $(".shopping-cart").append("<li><div class='total'><h5>subtotal : <span id='totalCart'>" + data.total_payable_amount + "</span></h5></div></li>");
                    $(".shopping-cart").append("<li><div class='buttons'><a href='{{ route('showCart') }}' class='view-cart'>viewcart</a> <a class='checkout' href='{{ route('user.checkout') }}' >checkout</a></div></li>");
                }
            },
            error: function(data) {
                console.log('Error Found : ' + data);
            }
        });
    }
    cartHeader();
    $(document).on("click",".remove-product",function() {
        let cartproduct_id = $(this).data('product');
        $('#cart_product_'+cartproduct_id).remove();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: delete_cart_product_url,
            data: {cartproduct_id:cartproduct_id},
            success: function(data) {
                if(data.status == 'success'){
                    toastr.success(data.message);
                }
            }
        });
    });
});