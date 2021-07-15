$(document).ready(function () {
    function Worldmap(markers){
        $('#world-map-markers').html("");
        var a = ["#6658dd"],
        e = $("#world-map-markers").data("colors");
        $("#world-map-markers").vectorMap({
            hoverColor: !1,
            hoverOpacity: 0.7,
            map: "world_mill_en",
            backgroundColor: "transparent",
            normalizeFunction: "polynomial",
            regionStyle: { initial: { fill: "#ced4da" } },
            markerStyle: { initial: { r: 9, fill: a[0], "fill-opacity": 0.9, stroke: "#fff", "stroke-width": 7, "stroke-opacity": 0.4 }, hover: { stroke: "#fff", "fill-opacity": 1, "stroke-width": 1.5 } },
            markers: markers,
        });
    }
    
    $(".refresh_cataegoryinfo").click(function () {
        getDashboardData(dashboard_filter_url);
    });
    $("#dashboard_refresh_btn").click(function () {
        $('#range-datepicker').val('');
        getDashboardData(dashboard_filter_url);
    });
    $("#range-datepicker").flatpickr({ 
        mode: "range",
        onClose: function(selectedDates, dateStr, instance) {
            console.log(selectedDates);
            getDashboardData(dashboard_filter_url,'',' ',selectedDates);
        }
    });
    getDashboardData(dashboard_filter_url);
    $(".yearSales").click(function () {
        var url = yearlyInfo_url;
        getDashboardData(dashboard_filter_url, 'yearly');
    });
    $(".monthlySales").click(function () {
        getDashboardData(dashboard_filter_url, 'monthly');
    });
    $(".weeklySales").click(function () {
        getDashboardData(dashboard_filter_url, 'weekly');
    });
    function getDashboardData(dashboard_filter_url, type = 'monthly', date_filter=''){
         $.getJSON(dashboard_filter_url,{type:type,date_filter:date_filter}, function (response) {
            $('#total_brands').html(response.data.total_brands);
            $('#total_vendor').html(response.data.total_vendor);
            $('#total_banners').html(response.data.total_banners);
            $('#total_products').html(response.data.total_products);
            $('#total_categories').html(response.data.total_categories);
            orderTopcatgory(response.data.labels, response.data.series);
            $('#total_pending_order').html(response.data.total_pending_order);
            $('#total_active_order').html(response.data.total_active_order);
            $('#total_rejected_order').html(response.data.total_rejected_order);
            $('#total_delivered_order').html(response.data.total_delivered_order);
            Worldmap(response.data.markers);
            // setTimeout(function(){
            //     $('.counter').counterup({
            //         delay: 100,
            //         time: 1200
            //     });
            // }, 3000);
            $('.counter').addClass('animated fadeInDownBig');
            if(type == 'yearly'){
                updateSales(response.data.revenue, response.data.sales, response.data.dates, "category");
            }else{
                updateSales(response.data.revenue, response.data.sales, response.data.dates, "datetime")
            }
        });
    }
    function updateSales(revenue, sales, dates, type_xaxis) {
        $('#sales-analytics').html("");
        var colors = ['#1abc9c', '#4a81d4'];
        var dataColors = $("#sales-analytics").data('colors');
        if (dataColors) {
            colors = dataColors.split(",");
        }
        var options = {
            series: [{
                name: 'Revenue',
                type: 'column',
                data: revenue
            }, {
                name: 'Sales',
                type: 'line',
                data: sales
            }],
            chart: {
                height: 378,
                type: 'line',
                offsetY: 10
            },
            stroke: {
                width: [2, 3]
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%'
                }
            },
            colors: colors,
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            labels: dates,
            xaxis: {
                type: type_xaxis
            },
            legend: {
                offsetY: 7,
            },
            grid: {
                padding: {
                    bottom: 20
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: "horizontal",
                    shadeIntensity: 0.25,
                    gradientToColors: undefined,
                    inverseColors: true,
                    opacityFrom: 0.75,
                    opacityTo: 0.75,
                    stops: [0, 0, 0]
                },
            },
            yaxis: [{
                title: {
                    text: 'Net Revenue',
                },
            }, {
                opposite: true,
                title: {
                    text: 'Number of Sales'
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#sales-analytics"), options);
        chart.render();
    }
    function orderTopcatgory(labels, series){
        $('#apexchartsfwg700r2').html("");
        var options = {
            series: series,
            labels: labels,
            chart: {
                width: 550,
                type: 'donut',
                offsetX: -100,
            },
            dataLabels: {
                enabled: false
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        show: false
                    }
                }
            }],
            legend: {
                position: 'bottom',
                offsetX: 0,
                height: 130,
            },
            noData: {
                text: "No Data Found",
                align: 'center',
                verticalAlign: 'middle',
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: "#000000",
                    fontSize: '14px',
                    fontFamily: "Helvetica"
                }
            }
        };
        var chart1 = new ApexCharts(document.querySelector("#apexchartsfwg700r2"), options);
        chart1.render();
    }

    
    function getUpdateSales(){
        $.getJSON(url, function (response) {
            updateSales(response.revenue, response.sales, response.dates, "datetime")
        });
    }
});