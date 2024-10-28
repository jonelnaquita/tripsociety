<?php
include '../inc/session.php';
include '../inc/header.php';


function totalDestinations($pdo)
{
    $stmt = $pdo->prepare("SELECT COUNT(id) AS total FROM tbl_location");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

function totalUsers($pdo)
{
    $stmt = $pdo->prepare("SELECT COUNT(id) AS total FROM tbl_user");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

// Data Analytics Chart for Location Count each User
$sql = "SELECT location, COUNT(*) as user_count FROM tbl_user GROUP BY location";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$location_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$labels = [];
$data = [];
foreach ($location_data as $row) {
    $labels[] = $row['location'];
    $data[] = $row['user_count'];
}
$labels_json = json_encode($labels);
$data_json = json_encode($data);



// Data analytics for hazard level base on review
$sql = "SELECT tl.location_name, 
               AVG(CASE 
                   WHEN tr.hazard = 'No Hazard' THEN 0 
                   WHEN tr.hazard = 'Very Low Hazard' THEN 20
                   WHEN tr.hazard = 'Low Hazard' THEN 40
                   WHEN tr.hazard = 'Moderate Hazard' THEN 60 
                   WHEN tr.hazard = 'High Hazard' THEN 80 
                   WHEN tr.hazard = 'Extreme Hazard' THEN 100 
                   ELSE NULL 
               END) as avg_hazard
        FROM tbl_review tr
        LEFT JOIN tbl_location tl ON tr.location_id = tl.id
        GROUP BY tl.location_name";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$hazard_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$dataPoints = [];

foreach ($hazard_data as $row) {
    $hazard_value = $row['avg_hazard'] ?? 0;
    $numeric_hazard_value = 0; // Default value for 'No Hazard'
    $hazard_level = '';

    if ($hazard_value <= 0) {
        $numeric_hazard_value = 0; // No Hazard
        $hazard_level = 'No Hazard';
    } elseif ($hazard_value < 20) {
        $numeric_hazard_value = 20; // Very Low Hazard
        $hazard_level = 'Very Low Hazard';
    } elseif ($hazard_value < 40) {
        $numeric_hazard_value = 40; // Low Hazard
        $hazard_level = 'Low Hazard';
    } elseif ($hazard_value < 60) {
        $numeric_hazard_value = 60; // Moderate Hazard
        $hazard_level = 'Moderate Hazard';
    } elseif ($hazard_value < 80) {
        $numeric_hazard_value = 80; // High Hazard
        $hazard_level = 'High Hazard';
    } else {
        $numeric_hazard_value = 100; // Extreme Hazard
        $hazard_level = 'Extreme Hazard';
    }

    // Add numeric hazard value and label for the chart
    $dataPoints[] = [
        'y' => $numeric_hazard_value,
        'label' => $row['location_name'] . " (" . $hazard_level . ")"
    ];
}

$dataPoints_json = json_encode($dataPoints);




//Top Desination Chart get the top rating
$sql = "SELECT tl.location_name, AVG(tr.rating) as avg_rating
        FROM tbl_review tr
        LEFT JOIN tbl_location tl ON tr.location_id = tl.id
        GROUP BY tl.location_name";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$rating_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$bar_data = [];
$location_ticks = [];

foreach ($rating_data as $index => $row) {
    $bar_data[] = [$index + 1, round($row['avg_rating'], 2)]; // Average rating rounded to 2 decimal places
    $location_ticks[] = [$index + 1, $row['location_name']];
}

// Convert PHP arrays to JSON for JavaScript
$bar_data_json = json_encode($bar_data);
$location_ticks_json = json_encode($location_ticks);




//Most frequently visited based on number of location id reviewed
$sql = "SELECT tl.location_name, COUNT(tr.location_id) as visit_count
        FROM tbl_review tr
        LEFT JOIN tbl_location tl ON tr.location_id = tl.id
        GROUP BY tl.location_name
        ORDER BY visit_count DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$visit_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data points for the chart
$labels1 = [];
$dataPoints1 = [];

foreach ($visit_data as $row1) {
    $labels1[] = $row1['location_name']; // Location names for the labels
    $dataPoints1[] = $row1['visit_count']; // Visit counts
}

// Convert PHP arrays to JSON for JavaScript
$labels_json1 = json_encode($labels1);
$dataPoints_json1 = json_encode($dataPoints1);




$sql = "SELECT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(travel_preferences, ',', numbers.n), ',', -1)) AS preference,
               COUNT(*) AS preference_count
        FROM tbl_user
        INNER JOIN (
            SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL
            SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL
            SELECT 9 UNION ALL SELECT 10
        ) numbers ON CHAR_LENGTH(travel_preferences) - CHAR_LENGTH(REPLACE(travel_preferences, ',', '')) >= numbers.n - 1
        GROUP BY preference
        ORDER BY preference_count DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$preferences_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Prepare data points for the chart
$labels = [];
$dataPoints = [];

foreach ($preferences_data as $row2) {
    $labels2[] = $row2['preference']; // Travel preferences for the labels
    $dataPoints2[] = $row2['preference_count']; // Counts of each preference
}

// Convert PHP arrays to JSON for JavaScript
$labels_json2 = json_encode($labels2);
$dataPoints_json2 = json_encode($dataPoints2);

?>


<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">

                </div>

            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">

            <div class="row">

                <div class="col">

                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="info-box">
                                <span class="info-box-icon text-center m-auto" style="width:300px;"><input type="text"
                                        class="knob" value="<?php echo totalDestinations($pdo); ?>" data-width="170"
                                        data-height="170" data-fgColor="#3c8dbc"></span>
                                <div class="info-box-content text-center">
                                    <span class="info-box-text font-weight-bold"
                                        style="font-size:90px; margin-top:-40px;"><?php echo totalDestinations($pdo); ?></span>
                                    <span class="info-box-number font-weight-normal"
                                        style="font-size:30px; margin-top:-30px;">
                                        Total Destinations
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="card p-3">
                                <h4 class="text-center">
                                    Top Destinations
                                </h4>
                                <div class="card-body">
                                    <div id="bar-chart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="row">
                        <div class="col">
                            <div class="card p-3">
                                <h4 class=" text-center">
                                    Most frequently visited destinations
                                </h4>
                                <canvas id="lineChartCanvas" width="400" height="200"></canvas>
                            </div>


                            <div class="card p-3">
                                <h4 class=" text-center">
                                    User Travel Preferences
                                </h4>

                                <div id="chartContainer" style="height: 350px; width: 100%;"></div>

                            </div>


                        </div>
                    </div>

                </div>





                <div class="col">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="card p-3">

                                <h4 class="text-center">User's Details</h4>


                                <div class="row mt-3">
                                    <div class="col">
                                        <div class="info-box-icon text-center m-auto" style="width:500px;"><input
                                                type="text" class="knob" value="<?php echo totalUsers($pdo); ?>"
                                                data-width="300" data-height="300" data-fgColor="gray"></div>

                                    </div>
                                    <div class="col m-auto text-center">
                                        <div class="info-box-content text-center">
                                            <span class="info-box-text font-weight-bold"
                                                style="font-size:90px;"><?php echo totalUsers($pdo); ?></span>
                                            <br>
                                            <span class="info-box-number font-weight-normal"
                                                style="font-size:30px; margin-top:-30px;">
                                                Total Users
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col m-auto text-center">
                                        <hr>
                                        <canvas id="donutChart"
                                            style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                                    </div>



                                </div>
                            </div>



                            <div class="row">
                                <div class="col">
                                    <div class="card p-3">
                                        <h4 class="text-center">Hazardous Area based on User Review</h4>

                                        <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include '../inc/footer.php';
?>


</body>

</html>
<script>
    window.onload = function () {
        var element = document.getElementById('monitoring');

        element.classList.add('active');
    };
</script>

<script>
    /*
* BAR CHART
* ---------
*/


    var bar_data = {
        data: <?php echo $bar_data_json; ?>,
        bars: { show: true }
    };

    $.plot('#bar-chart', [bar_data], {
        grid: {
            borderWidth: 1,
            borderColor: '#f3f3f3',
            tickColor: '#f3f3f3'
        },
        series: {
            bars: {
                show: true, barWidth: 0.5, align: 'center',
            },
        },
        colors: ['#3c8dbc'],
        xaxis: {
            ticks: <?php echo $location_ticks_json; ?>
        }
    });


    /* END BAR CHART */



</script>

<script>
    var ctx = document.getElementById('lineChartCanvas').getContext('2d');

    // Define the data
    var data = {
        labels: <?php echo $labels_json1; ?>,
        datasets: [{
            label: 'Visited',
            data: <?php echo $dataPoints_json1; ?>,
            backgroundColor: 'rgba(0, 0, 0, 0)', // Transparent fill for line
            borderColor: 'rgba(60,141,188,1)', // Line color
            borderWidth: 3 // Line width
        }]
    };

    // Define the options
    var options = {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...<?php echo $dataPoints_json1; ?>) + 10 // Adjust as needed
                }
            }]
        }
    };

    // Create the chart
    var lineChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: options
    });



    $(function () {
        //------------- 
        //- DONUT CHART - 
        //-------------
        var donutChartCanvas = $('#donutChart').get(0).getContext('2d');

        // Use PHP variables in JavaScript
        var donutData = {
            labels: <?php echo $labels_json; ?>,
            datasets: [
                {
                    data: <?php echo $data_json; ?>,
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12'], // Add more colors as needed
                }
            ]
        };

        var donutOptions = {
            maintainAspectRatio: false,
            responsive: true,
        };

        // Create pie or donut chart
        new Chart(donutChartCanvas, {
            type: 'doughnut',
            data: donutData,
            options: donutOptions
        });
    });

</script>


<script>


    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title: {
            text: ""
        },
        axisX: {
            interval: 1
        },
        axisY2: {
            interlacedColor: "#f3f3f3",
            gridColor: "#f3f3f3",
            title: ""
        },
        data: [{
            type: "bar",
            name: "Preferences",
            color: "rgba(60,141,188,1)",
            axisYType: "secondary",
            dataPoints: <?php echo json_encode(array_map(function ($label2, $count2) {
                return ['y' => $count2, 'label' => $label2];
            }, $labels2, $dataPoints2)); ?>
        }]
    });
    chart.render();



    var chart2 = new CanvasJS.Chart("chartContainer2", {
        animationEnabled: true,
        theme: "light2", // "light1", "light2", "dark1", "dark2"
        title: {
            text: ""
        },
        axisY: {
            title: "Hazard Level Meter"
        },
        data: [{
            type: "column",
            showInLegend: true,
            legendMarkerColor: "grey",
            legendText: "Hazard Level",
            dataPoints: <?php echo $dataPoints_json; ?>
        }]
    });
    chart2.render();


</script>



<script>
    var titleElement = document.getElementById("title");
    titleElement.innerHTML = "Dashboard";

    window.onload = function () {
        var element = document.getElementById('dashboard');
        element.classList.add('active');
    };

</script>