<?php

$dict = [
    'frequency' => 'MONTHLY',
    'from-date' => null,
    'to-date' => null,
];

if (isset($_POST['save'])) {
    if (isset($_POST['manual-dates'])) {
        if (isset($_POST['from-date']) && isset($_POST['to-date']) && $_POST['from-date'] != '' && $_POST['to-date'] != '') {
            $dict['from-date'] = $_POST['from-date'];
            $dict['to-date'] = $_POST['to-date'];
            $dict['frequency'] = '';
        } else {
            header('Location: ?p=dashboard');
            exit();
        }
    } else {
        $dict['frequency'] = $_POST['recurring-frequency'];
    }
}

$dashboard = $db->row('CALL `Get dashboard`(?,?,?)', $dict['from-date'], $dict['to-date'], $dict['frequency']);
$sales_category = $db->run('CALL `Get sales per category`(?,?,?)', $dict['from-date'], $dict['to-date'], $dict['frequency']);
$transactions_time = $db->run('CALL `Get transactions time`(?,?,?)', $dict['from-date'], $dict['to-date'], $dict['frequency']);
$sales_time = $db->run('CALL `Get sales time`(?,?,?)', $dict['from-date'], $dict['to-date'], $dict['frequency']);
$revenue_time = $db->run('CALL `Get revenue time`(?,?,?)', $dict['from-date'], $dict['to-date'], $dict['frequency']);
// date("Y-m-d H:i:s", strtotime($_POST["datentime"]))

?>

<!-- Main content -->
<div class="main-content" id="panel">
    <!-- Topnav -->
    <?php include 'includes/nav-top.php'; ?>
    <!-- Header -->
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <!-- <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Default</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Dashboards</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Default</li>
                            </ol>
                        </nav>
                    </div> -->
                    <div class="col-lg-12 col-9 text-right">
                        <!-- <a href="#" class="btn btn-sm btn-neutral">New</a> -->
                        <a data-toggle="modal" data-target="#modifyDates"
                                class="btn btn-sm btn-neutral">Filters</a>
                    </div>
                </div>
                <!-- Card stats -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total sales</h5>
                                        <span class="h2 font-weight-bold mb-0"><?php if (is_null($dashboard['@total_sales'])) {echo 'N/A';} else {echo $dashboard['@total_sales'];} ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                            <i class="ni ni-active-40"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-success mr-2">
                                        <!-- <i class="fa fa-arrow-up"></i> -->
                                        <?php if (is_null($dashboard['@total_sales_ratio'])) {echo 'N/A';} else {echo $dashboard['@total_sales_ratio'].'%';} ?>
                                    </span>
                                    <span class="text-nowrap">Since last time period</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total Profit</h5>
                                        <span class="h2 font-weight-bold mb-0"><?php if (is_null($dashboard['@total_profit'])) {echo 'N/A';} else {echo $dashboard['@total_profit'];} ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div
                                            class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                            <i class="ni ni-chart-pie-35"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-success mr-2">
                                        <?php if (is_null($dashboard['@total_profit_ratio'])) {echo 'N/A';} else {echo $dashboard['@total_profit_ratio']. '%';} ?>
                                    </span>
                                    <span class="text-nowrap">Since last time period</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">No. of Transactions</h5>
                                        <span class="h2 font-weight-bold mb-0"><?php if (is_null($dashboard['@total_transactions'])) {echo 'N/A';} else {echo $dashboard['@total_transactions'];} ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                            <i class="ni ni-money-coins"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-success mr-2">
                                        <?php if (is_null($dashboard['@total_transactions_ratio'])) {echo 'N/A';} else {echo $dashboard['@total_transactions_ratio'].'%';} ?>    
                                    </span>
                                    <span class="text-nowrap">Since last time period</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Best Selling</h5>
                                        <span class="h2 font-weight-bold mb-0">
                                            <?php if (is_null($dashboard['@best_selling'])) {echo 'N/A';} else {echo $dashboard['@best_selling'];} ?>    
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                            <i class="ni ni-chart-bar-32"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-success mr-2">
                                        <?php if (is_null($dashboard['@best_selling_prev'])) {echo 'N/A';} else {echo $dashboard['@best_selling_prev'];} ?>    
                                    </span>
                                    <span class="text-nowrap">Since last time period</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-8">
                <div class="card bg-default">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
                                <h5 class="h3 text-white mb-0">Number of sales</h5>
                            </div>
                            <!-- <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item mr-2 mr-md-0" data-toggle="chart"
                                        data-target="#chart-sales-dark"
                                        data-update='{"data":{"datasets":[{"data":[0, 20, 10, 30, 15, 40, 20, 60, 60]}]}}'
                                        data-prefix="$" data-suffix="k">
                                        <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                            <span class="d-none d-md-block">Month</span>
                                            <span class="d-md-none">M</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" data-toggle="chart" data-target="#chart-sales-dark"
                                        data-update='{"data":{"datasets":[{"data":[0, 20, 5, 25, 10, 30, 15, 40, 40]}]}}'
                                        data-prefix="$" data-suffix="k">
                                        <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                            <span class="d-none d-md-block">Week</span>
                                            <span class="d-md-none">W</span>
                                        </a>
                                    </li>
                                </ul>
                            </div> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <!-- Chart wrapper -->
                            <canvas id="chart-sales-dark" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                                <h5 class="h3 mb-0">Total transactions</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <canvas id="chart-bars" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-8">
                <div class="card bg-default">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
                                <h5 class="h3 text-white mb-0">Revenue</h5>
                            </div>
                            <!-- <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item mr-2 mr-md-0" data-toggle="chart"
                                        data-target="#chart-revenue-dark"
                                        data-update='{"data":{"datasets":[{"data":[0, 20, 10, 30, 15, 40, 20, 60, 60]}]}}'
                                        data-prefix="$" data-suffix="k">
                                        <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                            <span class="d-none d-md-block">Month</span>
                                            <span class="d-md-none">M</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" data-toggle="chart" data-target="#chart-revenue-dark"
                                        data-update='{"data":{"datasets":[{"data":[0, 20, 5, 25, 10, 30, 15, 40, 40]}]}}'
                                        data-prefix="$" data-suffix="k">
                                        <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                            <span class="d-none d-md-block">Week</span>
                                            <span class="d-md-none">W</span>
                                        </a>
                                    </li>
                                </ul>
                            </div> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <!-- Chart wrapper -->
                            <canvas id="chart-revenue-dark" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                                <h5 class="h3 mb-0">Sales per category</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <canvas id="chart-pie" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modifyDates" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Filters</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form role="form" action="" method="post">
            <fieldset>
                <div class="form-group mb-3">
                    <label for="frequency"> Frequency</label>
                    <div class="input-group input-group-merge input-group-alternative modal-div-input">
                        <select class="form-control modal-div-input" name="recurring-frequency">
                            <option value="DAILY" <?php if ($dict['frequency'] == 'DAILY') echo 'selected';?>>DAILY</option>
                            <option value="WEEKLY" <?php if ($dict['frequency'] == 'WEEKLY') echo 'selected';?>>WEEKLY</option>
                            <option value="MONTHLY" <?php if ($dict['frequency'] == 'MONTHLY') echo 'selected';?>>MONTHLY</option>
                        </select>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="manual-dates"><?php echo 'Set dates manually'; ?></label>
                    <input type="checkbox" id="manual-dates" name="manual-dates" value="Yes">
                </div>
                <div id="range" hidden>

                    <div class="form-group mb-3">
                        <label for="from-date"> From</label>
                        <div class="input-group input-group-merge input-group-alternative modal-div-input">
                            <input class="form-control modal-div-input" name="from-date" type="datetime-local">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="to-date"> To</label>
                        <div class="input-group input-group-merge input-group-alternative modal-div-input">
                            <input class="form-control modal-div-input" name="to-date" type="datetime-local">
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button name="save" type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        $('#manual-dates').on('click change', function() {
            var checked = $('#manual-dates').is(':checked');
            $('#range').prop('hidden', !checked);
        });

        //
        // Bars chart
        //

        var BarsChart = (function() {

        //
        // Variables
        //

        var $chart = $('#chart-bars');


        //
        // Methods
        //

        // Init chart
        function initChart($chart) {
            var dataContainer = <?= json_encode($transactions_time) ?>;

            var labels = [];
            var data = [];
            dataContainer.forEach(function(row){
                labels.push(moment(row['date']).format('DD MMM'));
                data.push(row['num_transactions']);
            });
            // Create chart
            var ordersChart = new Chart($chart, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Transactions',
                        data: data
                    }],
                    options: {
                        scales: {
                            xAxes: [{
                                type: 'time',
                                time: {
                                    unit: 'day'
                                },
                            }],
                        }
                    }
                }
            });

            // Save to jQuery object
            $chart.data('chart', ordersChart);
        }


        // Init chart
        if ($chart.length) {
            initChart($chart);
        }

        })();

        'use strict';

        //
        // Sales chart
        //

        var SalesChart = (function() {

        // Variables

        var $chart = $('#chart-sales-dark');


        // Methods

        function init($chart) {
            var dataContainer = <?= json_encode($sales_time) ?>;
            console.log(dataContainer);
            var labels = [];
            var data = [];
            dataContainer.forEach(function(row){
                labels.push(moment(row['date']).format('DD MMM'));
                data.push(row['num_sales']);
            });

            var salesChart = new Chart($chart, {
            type: 'line',
            options: {
                scales: {
                yAxes: [{
                    gridLines: {
                    lineWidth: 1,
                    color: Charts.colors.gray[900],
                    zeroLineColor: Charts.colors.gray[900]
                    },
                    // ticks: {
                    //     callback: function(value) {
                    //         if (!(value % 10)) {
                    //         return '$' + value + 'k';
                    //         }
                    //     }
                    // }
                }]
                },
                // tooltips: {
                // callbacks: {
                //     label: function(item, data) {
                //     var label = data.datasets[item.datasetIndex].label || '';
                //     var yLabel = item.yLabel;
                //     var content = '';

                //     if (data.datasets.length > 1) {
                //         content += '' + label + '';
                //     }

                //     content += '' + yLabel + '';
                //     return content;
                //     }
                // }
                // }
            },
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales',
                    data: data
                }]
            }
            });

            // Save to jQuery object

            $chart.data('chart', salesChart);

            };


            // Events

            if ($chart.length) {
            init($chart);
            }

        })();

        var RevenueChart = (function() {
            var dataContainer = <?= json_encode($revenue_time) ?>;
            console.log(dataContainer);
            var labels = [];
            var data = [];
            dataContainer.forEach(function(row){
                labels.push(moment(row['date']).format('DD MMM'));
                data.push(row['revenue']);
            });
            var $chart = $('#chart-revenue-dark');
            function init($chart) {
            var revenueChart = new Chart($chart, {
                type: 'line',
                options: {
                    scales: {
                        yAxes: [{
                        gridLines: {
                            lineWidth: 1,
                            color: Charts.colors.gray[900],
                            zeroLineColor: Charts.colors.gray[900]
                        },
                        // ticks: {
                        //     callback: function(value) {
                        //     if (!(value % 10)) {
                        //         return '$' + value + 'k';
                        //     }
                        //     }
                        // }
                        }]
                    },
                    // tooltips: {
                    //     callbacks: {
                    //     label: function(item, data) {
                    //         var label = data.datasets[item.datasetIndex].label || '';
                    //         var yLabel = item.yLabel;
                    //         var content = '';
                    //         if (data.datasets.length > 1) {
                    //         content += '' + label + '</span>';
                    //         }
                    //         content += '' + yLabel + '';
                    //         return content;
                    //     }
                    //     }
                    // }
                },
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue',
                        data: data
                    }]
                }
            });
            $chart.data('chart', revenueChart);
            };
            if ($chart.length) {
            init($chart);
            }
        })();

        ////DONUT TOUCH ME!
        var ProductsPieChart = (function() {
            var dataContainer = <?= json_encode($sales_category); ?>;
            var category_data = {'SNACKS':'0', 'DRINKS':'0', 'ELECTRONICS':'0', 'MEDICAL':'0'};
            dataContainer.forEach(function(row) {
                category_data[row['category']] = row['quantity'];
            });
            var data = [];
            var labels = []
            for (const [key, value] of Object.entries(category_data)) {
                if (value != '0') {
                    labels.push(key);
                    data.push(value);
                }
            }
            
            var $chart = $('#chart-pie');
            function init($chart) {
                var productsPieChart = new Chart($chart, {
                    type: 'doughnut',
                    options: {cutoutPercentage: 50},
                    data : {
                        labels: labels,
                        datasets: [{data: data,
                                backgroundColor:['#ff6384', '#36a2eb', '#cc65fe', '#ffce56'],
                                hoverBorderColor:['#ff6384', '#36a2eb', '#cc65fe', '#ffce56'],
                                hoverBorderWidth: 10,
                                hoverBackgroundColor:['#bf435e','#3988bd','#9639c4','#c7961a']}]
                    }
                });
                
                $chart.data('chart', productsPieChart);
            };

            init($chart);
            })();
        })
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"
    integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw=="
    crossorigin="anonymous"></script>