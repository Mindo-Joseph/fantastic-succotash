$(document).ready(function () {
    $("#range-datepicker").flatpickr({ 
        mode: "range",
        onClose: function(selectedDates, dateStr, instance) {
            getDashboardData(dashboard_filter_url);
        }
    });
    getDashboardData(dashboard_filter_url);

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
    function getDashboardData(dashboard_filter_url){
         $.getJSON(dashboard_filter_url, function (response) {
            $('#total_brands').html(response.data.total_brands);
            $('#total_vendor').html(response.data.total_vendor);
            $('#total_banners').html(response.data.total_banners);
            $('#total_products').html(response.data.total_products);
            $('#total_categories').html(response.data.total_categories);
            $('#total_pending_order').html(response.data.total_pending_order);
            $('#total_active_order').html(response.data.total_active_order);
            $('#total_rejected_order').html(response.data.total_rejected_order);
            $('#total_delivered_order').html(response.data.total_delivered_order);
        });
    }
    var url = monthlyInfo_url;
    getUpdateSales(url);
    updateCategoryInfo();
    $(".yearSales").click(function () {
        var url = yearlyInfo_url;
        $.getJSON(url, function (response) {
            updateSales(response.revenue, response.sales, response.dates, "category")
        });
    });
    $(".monthlySales").click(function () {
        var url = monthlyInfo_url;
        getUpdateSales(url);
    });
    $(".weeklySales").click(function () {
        var url = weeklyInfo_url;
    });
     $(".refresh_salesChart").click(function () {
        var url = monthlyInfo_url;
        getUpdateSales(url);
    });
    function getUpdateSales(){
        $.getJSON(url, function (response) {
            updateSales(response.revenue, response.sales, response.dates, "datetime")
        });
    }
    function updateCategoryInfo() {
        $('#apexchartsfwg700r2').html("");
        var url = categoryInfo_url;
        $.getJSON(url, function (response) {
            var options = {
                series: response.orders,
                labels: response.names,
                chart: {
                    width: 350,
                    type: 'donut',
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
                    position: 'right',
                    offsetY: 0,
                    height: 230,
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
        });
    }
    $(".refresh_cataegoryinfo").click(function () {
        updateCategoryInfo();
    });
   
});