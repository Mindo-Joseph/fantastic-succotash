$(document).ready(function () {
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

    var url = monthlyInfo_url;
    updateCategoryInfo();
    $.getJSON(url, function (response) {
        updateSales(response.revenue, response.sales, response.dates, "datetime")
    });
    $(".yearSales").click(function () {
        var url = yearlyInfo_url;
        $.getJSON(url, function (response) {
            updateSales(response.revenue, response.sales, response.dates, "category")
        });
    });
    $(".monthlySales").click(function () {
        var url = monthlyInfo_url;
        $.getJSON(url, function (response) {
            updateSales(response.revenue, response.sales, response.dates, "datetime")
        });
    });
    $(".weeklySales").click(function () {
        var url = weeklyInfo_url;
        $.getJSON(url, function (response) {
            updateSales(response.revenue, response.sales, response.dates, "datetime")
        });
    });

    function updateCategoryInfo() {
        $('#apexchartsfwg700r2').html("");
        var url = categoryInfo_url;
        $.getJSON(url, function (response) {
            var options = {
                series: response.orders,
                labels: response.names,
                chart: {
                    width: 380,
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
                    position: 'left',
                    // horizontalAlign: 'center', 
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

    $(".refresh_salesChart").click(function () {
        var url = monthlyInfo_url;
        $.getJSON(url, function (response) {
            updateSales(response.revenue, response.sales, response.dates, "datetime")
        });
    });
});