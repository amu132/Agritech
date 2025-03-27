<?php
include ('fsession.php');
ini_set('memory_limit', '-1');

if(!isset($_SESSION['farmer_login_user'])){
    header("location: ../index.php"); // Redirecting To Home Page
}

$query4 = "SELECT * from farmerlogin where email='$user_check'";
$ses_sq4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($ses_sq4);
$para1 = $row4['farmer_id'];
$para2 = $row4['farmer_name'];

// Fetch district name of the farmer
$display_district = "SELECT F_District FROM farmerlogin WHERE email='$user_check'";
$display_district_result = mysqli_query($conn, $display_district);
$display_district_name = mysqli_fetch_array($display_district_result);
$District_name_farmer = trim($display_district_name[0]);

// Weather API City_Id Code
$url = 'static/citylist.json'; // Path to the JSON file containing city data
$data = file_get_contents($url);
$districts = json_decode($data);

$district_weather_id = 0;

// Find the corresponding city ID from JSON
foreach ($districts as $district) {
    if ($district->name == $District_name_farmer) {
        $district_weather_id = $district->id;
        break;
    }
}

// Set default city ID to Mumbai if no match is found
if ($district_weather_id <= 0) {
    $district_weather_id = 1275339; // Mumbai City ID
}

$city_weather_id = strval($district_weather_id);

date_default_timezone_set("Asia/Kolkata");
$apiKey = "870887df4d2b01335921fe396c69a360"; // Your API Key
$cityId = $city_weather_id;

// OpenWeatherMap API URL
$googleApiUrl = "https://api.openweathermap.org/data/2.5/forecast?id=" . $cityId . "&lang=en&units=metric&APPID=" . $apiKey;
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response);
$currentTime = time();
$forecast = $data->list;
?>

<!DOCTYPE html>
<html>

<?php include ('fheader.php'); ?>

<body class="bg-white" id="top">

<?php include ('fnav.php'); ?>

<section class="section section-shaped section-lg">
    <div class="shape shape-style-1 shape-primary">
        <span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span><span></span>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <span class="badge badge-danger badge-pill mb-3">Weather Forecast</span>
            </div>
        </div>

        <div class="row row-content">
            <div class="col-md-12 mb-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card text-white bg-gradient-secondary mb-3">
                            <div class="card-header">
                                <span id="openweathermap-widget-9"></span> 
                            </div>

                            <script>
                                window.myWidgetParam ? window.myWidgetParam : window.myWidgetParam = [];
                                window.myWidgetParam.push({
                                    id: 9,
                                    cityid: '<?php echo $cityId ?>',
                                    appid: '870887df4d2b01335921fe396c69a360',
                                    units: 'metric',
                                    containerid: 'openweathermap-widget-9'
                                });
                                (function() {
                                    var script = document.createElement('script');
                                    script.async = true;
                                    script.charset = "utf-8";
                                    script.src = "//openweathermap.org/themes/openweathermap/assets/vendor/owm/js/weather-widget-generator.js";
                                    var s = document.getElementsByTagName('script')[0];
                                    s.parentNode.insertBefore(script, s);
                                })();
                            </script>

                            <div class="card-body text-dark">
                                <table class="table table-striped table-hover table-bordered bg-gradient-white text-center display" id="myTable">
                                    <thead>
                                        <tr class="font-weight-bold text-default">
                                            <th><center>Date</center></th>
                                            <th><center>Time</center></th>                    
                                            <th><center>Temperature (Max / Min)</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>Humidity</center></th>
                                            <th><center>Wind</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

<?php foreach ($forecast as $f): ?>
    <tr class="text-center">
        <td><?php echo substr($f->dt_txt, 0, 10); ?></td>
        <td><?php echo substr($f->dt_txt, 11); ?></td>
        <td>
            <img src="http://openweathermap.org/img/w/<?php echo $f->weather[0]->icon; ?>.png" class="weather-icon" />
            <?php echo $f->main->temp_max; ?> &#8451; &nbsp;
            <span class="min-temperature"><?php echo $f->main->temp_min; ?> &#8451;</span>
        </td>
        <td><?php echo $f->weather[0]->main . ', ' . $f->weather[0]->description; ?></td>
        <td><?php echo $f->main->humidity; ?> &#37;</td>
        <td><?php echo $f->wind->speed; ?> KM/H</td>
    </tr>
<?php endforeach; ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</section>

<?php require("footer.php"); ?>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>

</body>
</html>
