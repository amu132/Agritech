<?php
define('NEWS_API_KEY', 'eeb809ccada1415896a76735c42a097a');  // Your API key

// Properly encode the query parameters
$query = urlencode('agriculture');
$url = "https://newsapi.org/v2/everything?" . http_build_query([
    'q' => $query,
    'language' => 'en',
    'sortBy' => 'publishedAt',
    'pageSize' => 20,
    'apiKey' => NEWS_API_KEY
]);

// Use CURL instead of file_get_contents
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTPHEADER => [
        'X-Api-Key: ' . NEWS_API_KEY,
        'Accept: application/json'
    ]
]);

$response = curl_exec($ch);
$error = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

header('Content-Type: application/json');

// Return detailed error information
$result = [
    'success' => ($httpCode === 200),
    'httpCode' => $httpCode,
    'error' => $error,
    'response' => json_decode($response),
    'contentType' => $contentType,
    'url' => $url
];

echo json_encode($result, JSON_PRETTY_PRINT); 