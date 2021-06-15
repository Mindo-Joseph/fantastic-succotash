$(document).ready(function() {
    if($("#current_location").length){
        var x = document.getElementById("current_location");
        // x.value = '30.71542708142086, 76.80557399857302';
        x.value = '41.819138300533176, 90.07413853041372';
        x.value = '17.022126325645715, 137.31096954224145'
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "/homepage",
            data: {"latitude": "30.71542708142086", "longitude": "76.80557399857302"},
            success: function(response) {
                if(response.status == 'Success'){
                    console.log(response.data.vendorsHtml);
                    $('.product-4').slick('destroy');
                    $(".product-4").html(response.data.vendorsHtml);
                    $('.product-4').slick({
                        infinite: true,
                        speed: 300,
                        slidesToShow: 4,
                        slidesToScroll: 4,
                        autoplay: true,
                        autoplaySpeed: 3000,
                        responsive: [{
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 3
                            }
                        },
                        {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2
                            }
                        }]
                    });
                }
            }
        });
    }
});