<?php
$data['photos'] = $modx->runSnippet("CarPhotos", ['fromJson' => $data['tv.car_photos']]);
$data['photo'] = $modx->runSnippet("CarPhotos", ['fromJson' => $data['tv.car_photos'], 'firstPhoto' => 1]);

return $data;
