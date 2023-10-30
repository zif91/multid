<?php
$data['car_photos'] = json_decode($data['tv.car_photos'], true);

if (!empty($data['car_photos']) && !empty($data['car_photos']['fieldValue'])) {
    $data['photos'] = [];
    foreach ($data['car_photos']['fieldValue'] as $photo) {
        $data['photos'][] = $photo['image'];
    }
    $data['photo'] = $data['photos'][0];
}

return $data;
