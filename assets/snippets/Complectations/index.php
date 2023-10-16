<?php
include_once __DIR__."/Controllers/Controller.php";

$car_id = !empty($car_id) ? $car_id : $modx->documentIdentifier;

$controller = new \EvolutionCMS\Complectations\Controllers\Controller($modx);
return $controller->car($car_id)
    ->toArray();
