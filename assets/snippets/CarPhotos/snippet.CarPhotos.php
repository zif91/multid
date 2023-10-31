<?php
$fromJson = !empty($fromJson) ? $fromJson : "[]";
$firstPhoto = !empty($firstPhoto) ? (int) $firstPhoto : 0;

$photo = "";
$photos = [];
$decoded = json_decode($fromJson, true);

if (!empty($decoded) && !empty($decoded['fieldValue'])) {
    foreach ($decoded['fieldValue'] as $photo) {
        $photos[] = $photo['image'];
    }
    $photo = $photos[0];
}

if ($firstPhoto === 1) {
    return $photo;
}

return $photos;
