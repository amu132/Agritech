<?php
include ('fsession.php');
ini_set('memory_limit', '-1');

if(!isset($_SESSION['farmer_login_user'])){
    header("location: ../index.php");
}
$query4 = "SELECT * from farmerlogin where email='$user_check'";
$ses_sq4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($ses_sq4);
$para1 = $row4['farmer_id'];
$para2 = $row4['farmer_name'];
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
            <span class="badge badge-danger badge-pill mb-3">Prediction</span>
        </div>
    </div>

    <div class="row row-content">
        <div class="col-md-12 mb-3">
            <div class="card text-white bg-gradient-success mb-3">
                <form role="form" action="#" method="post">
                    <div class="card-header">
                        <span class="text-info display-4"> Yield Prediction </span>
                    </div>
                    <div class="card-body text-dark">
                        <table class="table table-striped table-hover table-bordered bg-gradient-white text-center display" id="myTable">
                            <thead>
                                <tr class="font-weight-bold text-default">
                                    <th><center>State</center></th>
                                    <th><center>District</center></th>
                                    <th><center>Season</center></th>
                                    <th><center>Crop</center></th>
                                    <th><center>Area</center></th>
                                    <th><center>Prediction</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td>
                                        <div class="form-group">
                                            <select name="state" class="form-control" required>
                                                <option value="Maharashtra">Maharashtra</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select id="district" name="district" class="form-control" required>
                                                <option value="">Select a district</option>
                                                <option value="AHMEDNAGAR">Ahmednagar</option>
                                                <option value="PUNE">Pune</option>
                                                <option value="NAGPUR">Nagpur</option>
                                                <option value="MUMBAI">Mumbai</option>
                                                <option value="NASIK">Nasik</option>
                                                <option value="AURANGABAD">Aurangabad</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="Season" class="form-control" id="season" required>
                                                <option value="">Select Season ...</option>
                                                <option value="Kharif">Kharif</option>
                                                <option value="Rabi">Rabi</option>
                                                <option value="Summer">Summer</option>
                                                <option value="Whole Year">Whole Year</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select id="crop" class="form-control" name="crops" required>
                                                <option value="">Select crop</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" step="0.01" id="area" name="area" placeholder="Area in Hectares" required class="form-control">
                                            <small id="area-range" class="form-text text-muted"></small>
                                        </div>
                                    </td>
                                    <td>
                                        <center>
                                            <div class="form-group">
                                                <button type="submit" value="Yield" name="Yield_Predict" class="btn btn-success btn-submit">Predict</button>
                                            </div>
                                        </center>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

            <div class="card text-white bg-gradient-success mb-3">
                <div class="card-header">
                    <span class="text-success display-4"> Result </span>
                </div>
                <h4>
                <?php 
                if(isset($_POST['Yield_Predict'])){
                    $state = trim($_POST['state']);
                    $district = trim($_POST['district']);
                    $season = trim($_POST['Season']);
                    $crops = trim($_POST['crops']);
                    $area = trim($_POST['area']);

                    if(!is_numeric($area) || $area <= 0) {
                        echo "<div class='alert alert-danger'>Please enter a valid area</div>";
                    } else {
                        echo "Predicted crop yield (in Quintal) is: ";
                        
                        try {
                            $pythonScript = __DIR__ . "/ML/yield_prediction/yield_prediction.py";
                            
                            if (!file_exists($pythonScript)) {
                                throw new Exception("Prediction script not found");
                            }
                            
                            $command = sprintf(
                                'python %s %s %s %s %s %s 2>&1',
                                escapeshellarg($pythonScript),
                                escapeshellarg($state),
                                escapeshellarg($district),
                                escapeshellarg($season),
                                escapeshellarg($crops),
                                escapeshellarg($area)
                            );
                            
                            $output = shell_exec($command);
                            
                            if($output === null) {
                                echo "<div class='alert alert-danger'>Error running prediction model</div>";
                            } else {
                                $output = trim($output);
                                if (strpos($output, "Error:") === 0) {
                                    echo "<div class='alert alert-danger'>" . htmlspecialchars($output) . "</div>";
                                } else {
                                    $value = floatval($output);
                                    if ($value >= 0) {
                                        $yield_per_hectare = $value / floatval($area);
                                        echo "<div class='alert alert-success'>";
                                        echo "<h4 style='font-weight: bold;'>Prediction Results:</h4>";
                                        echo "<p>Total Production: " . number_format($value, 2) . " Quintals</p>";
                                        echo "<p>Yield per Hectare: " . number_format($yield_per_hectare, 2) . " Quintals/Ha</p>";
                                        echo "<p>Area: " . number_format($area, 2) . " Hectares</p>";
                                        echo "</div>";
                                    } else {
                                        echo "<div class='alert alert-danger'>Invalid prediction value received: " . htmlspecialchars($output) . "</div>";
                                    }
                                }
                            }
                        } catch(Exception $e) {
                            echo "<div class='alert alert-danger'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</div>";
                        }
                    }
                }
                ?>
                </h4>
            </div>
        </div>
    </div>
</div>
</section>

<script>
// Function to fetch and populate seasons
function getValidCombinations() {
    const district = document.getElementById('district').value;
    if (!district) return;

    fetch(`get_valid_combinations.php?district=${encodeURIComponent(district)}`)
        .then(response => response.json())
        .then(data => {
            const seasonSelect = document.getElementById('season');
            seasonSelect.innerHTML = '<option value="">Select Season ...</option>';
            
            // Add valid seasons
            data.seasons.sort().forEach(season => {
                const option = document.createElement('option');
                option.value = season.trim(); // Trim any extra spaces
                option.textContent = season.trim();
                seasonSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching seasons:', error));
}

// Function to fetch and populate crops
function getValidCrops() {
    const district = document.getElementById('district').value;
    const season = document.getElementById('season').value;
    if (!district || !season) return;

    fetch(`get_valid_crops.php?district=${encodeURIComponent(district)}&season=${encodeURIComponent(season)}`)
        .then(response => response.json())
        .then(data => {
            const cropSelect = document.getElementById('crop');
            cropSelect.innerHTML = '<option value="">Select crop</option>';
            
            if (data && data.crops) {
                data.crops.sort().forEach(crop => {
                    const option = document.createElement('option');
                    option.value = crop;
                    option.textContent = crop;
                    cropSelect.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error fetching crops:', error));
}

// Function to fetch and display area range
function updateAreaRange() {
    const district = document.getElementById('district').value;
    const season = document.getElementById('season').value;
    const crop = document.getElementById('crop').value;
    const areaInput = document.getElementById('area');
    const areaRange = document.getElementById('area-range');
    
    if (!district || !season || !crop) return;

    fetch(`get_area_range.php?district=${encodeURIComponent(district)}&season=${encodeURIComponent(season)}&crop=${encodeURIComponent(crop)}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                areaInput.min = data.min;
                areaInput.max = data.max;
                areaInput.title = `Recommended area range: ${data.min.toFixed(2)} - ${data.max.toFixed(2)} hectares\nAverage: ${data.avg.toFixed(2)} hectares`;
                areaRange.textContent = `Recommended area: ${data.min.toFixed(2)} - ${data.max.toFixed(2)} hectares`;
            }
        })
        .catch(error => console.error('Error fetching area range:', error));
}

// Add event listeners
document.getElementById('district').addEventListener('change', () => {
    getValidCombinations();
    document.getElementById('crop').innerHTML = '<option value="">Select crop</option>';
    document.getElementById('area-range').textContent = '';
});

document.getElementById('season').addEventListener('change', () => {
    getValidCrops();
    document.getElementById('area-range').textContent = '';
});

document.getElementById('crop').addEventListener('change', updateAreaRange);
</script>

<?php require("footer.php");?>

</body>
</html>

function get_historical_stats($district, $season, $crop) {
    $csv_file = __DIR__ . "/ML/yield_prediction/crop_production_maharashtra_cleaned.csv";
    $data = array_map('str_getcsv', file($csv_file));
    $header = array_shift($data);
    
    $district_idx = array_search('District_Name', $header);
    $season_idx = array_search('Season', $header);
    $crop_idx = array_search('Crop', $header);
    $area_idx = array_search('Area', $header);
    $production_idx = array_search('Production', $header);
    
    $yields = array();
    foreach ($data as $row) {
        if ($row[$district_idx] === $district && 
            trim($row[$season_idx]) === trim($season) && 
            $row[$crop_idx] === $crop) {
            $area = floatval($row[$area_idx]);
            $production = floatval($row[$production_idx]);
            if ($area > 0) {
                $yields[] = $production / $area;
            }
        }
    }
    
    if (empty($yields)) {
        return null;
    }
    
    return array(
        'min_yield' => min($yields),
        'max_yield' => max($yields),
        'avg_yield' => array_sum($yields) / count($yields)
    );
}
