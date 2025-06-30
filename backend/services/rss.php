<?php
header("Content-Type: application/rss+xml; charset=UTF-8");

require_once __DIR__ . '/../repositories/database/db.php';
require_once __DIR__ . '/../repositories/SearchRepository.php';

$repository = new SearchRepository($conn);
$filters = $_GET;

if (isset($filters['type[]'])) {
    $filters['type'] = $filters['type[]'];
    unset($filters['type[]']);
}

$pets = $repository->getFilteredPets($filters);

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss version="2.0"></rss>');
$channel = $xml->addChild('channel');

$channel->addChild('title', 'FurryFriends - Pet Adoption Feed');
$channel->addChild('link', 'http://localhost/Pet_Adoption/frontend/view/pages/searchView.html');
$channel->addChild('description', 'Latest pets available for adoption');
$channel->addChild('language', 'en-us');
$channel->addChild('pubDate', date(DATE_RSS));

foreach ($pets as $pet) {
    $item = $channel->addChild('item');
    $item->addChild('title', htmlspecialchars($pet['name']));
    $item->addChild('description', htmlspecialchars(
        "Type: {$pet['animal_type']}, Breed: {$pet['breed']}, Age: {$pet['age']} years, Size: {$pet['size']}, Location: {$pet['owner_country']}, {$pet['owner_county']}"
    ));
    $link = "http://localhost/Pet_Adoption/frontend/view/pages/pet_profile.html?pet_id={$pet['id']}";
    $item->addChild('link', $link);
    $guid = $item->addChild('guid', $link);
    $guid->addAttribute('isPermaLink', 'true');

    if (!empty($pet['created_at'])) {
        $item->addChild('pubDate', date(DATE_RSS, strtotime($pet['created_at'])));
    } else {
        $item->addChild('pubDate', date(DATE_RSS));
    }
}

echo $xml->asXML();
