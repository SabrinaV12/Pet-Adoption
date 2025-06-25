<?php

require_once __DIR__ . '/../repositories/SearchRepository.php';
session_start();

$repository = new SearchRepository();

$countries = $repository->getDistinctCountries();
$counties = $repository->getDistinctCounties();

$filters = $_GET;
$result = $repository->getFilteredPets($filters);

include __DIR__ . '/../../frontend/pages/search.php';