<?php
function get_valid_combinations($district) {
    $csv_file = __DIR__ . "/ML/yield_prediction/crop_production_maharashtra_cleaned.csv";
    $data = array_map('str_getcsv', file($csv_file));
    $header = array_shift($data);
    
    $district_idx = array_search('District_Name', $header);
    $season_idx = array_search('Season', $header);
    $crop_idx = array_search('Crop', $header);
    
    $seasons = array();
    $crops = array();
    
    foreach ($data as $row) {
        if ($row[$district_idx] === $district) {
            $seasons[trim($row[$season_idx])] = true;
            $crops[$row[$crop_idx]] = true;
        }
    }
    
    return array(
        'seasons' => array_keys($seasons),
        'crops' => array_keys($crops)
    );
}

if (isset($_GET['district'])) {
    header('Content-Type: application/json');
    echo json_encode(get_valid_combinations($_GET['district']));
}
?> 