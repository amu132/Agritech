<?php
if (isset($_GET['district']) && isset($_GET['season'])) {
    $district = $_GET['district'];
    $season = $_GET['season'];
    
    $csv_file = __DIR__ . "/ML/yield_prediction/crop_production_maharashtra_cleaned.csv";
    $data = array_map('str_getcsv', file($csv_file));
    $header = array_shift($data);
    
    $district_idx = array_search('District_Name', $header);
    $season_idx = array_search('Season', $header);
    $crop_idx = array_search('Crop', $header);
    
    $crops = array();
    foreach ($data as $row) {
        if ($row[$district_idx] === $district && 
            trim($row[$season_idx]) === trim($season)) {
            $crops[$row[$crop_idx]] = true;
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode(array('crops' => array_keys($crops)));
}
?> 