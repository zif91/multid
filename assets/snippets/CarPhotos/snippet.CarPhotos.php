<?php
$fromJson = !empty($fromJson) ? $fromJson : "[]";
$firstPhoto = !empty($firstPhoto) ? (int) $firstPhoto : 0;

$photos = [];
$decoded = json_decode($fromJson, true);

if (!empty($decoded) && !empty($decoded['fieldValue'])) {
    foreach ($decoded['fieldValue'] as $photo) {
        $photos[] = $photo['image'];
    }

    if ($firstPhoto === 1) {
        return $photos[0];
    }
}

return $photos;
