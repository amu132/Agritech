<?php
if (isset($_GET['district']) && isset($_GET['season']) && isset($_GET['crop'])) {
    $district = $_GET['district'];
    $season = $_GET['season'];
    $crop = $_GET['crop'];
    
    $csv_file = __DIR__ . "/ML/yield_prediction/crop_production_maharashtra_cleaned.csv";
    $data = array_map('str_getcsv', file($csv_file));
    $header = array_shift($data);
    
    $district_idx = array_search('District_Name', $header);
    $season_idx = array_search('Season', $header);
    $crop_idx = array_search('Crop', $header);
    $area_idx = array_search('Area', $header);
    
    $areas = array();
    foreach ($data as $row) {
        if ($row[$district_idx] === $district && 
            trim($row[$season_idx]) === $season && 
            $row[$crop_idx] === $crop) {
            $areas[] = floatval($row[$area_idx]);
        }
    }
    
    header('Content-Type: application/json');
    if (empty($areas)) {
        echo json_encode(null);
    } else {
        echo json_encode(array(
            'min' => min(1.0, min($areas) / 100),
            'max' => max($areas) * 2,
            'avg' => array_sum($areas) / count($areas)
        ));
    }
}
?> 