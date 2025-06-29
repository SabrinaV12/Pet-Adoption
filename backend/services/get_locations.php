<?php
require_once __DIR__ . '/../repositories/database/db.php';
require_once __DIR__ . '/../repositories/SearchRepository.php';

header('Content-Type: application/json');

$repo = new SearchRepository($conn);
$countries = $repo->getDistinctCountries();
$counties = $repo->getDistinctCounties();

echo json_encode([
    'countries' => array_column($countries, 'country'),
    'counties' => array_column($counties, 'county')
]);
