<?php
if (!empty($_GET['q']) && strpos($_GET['q'], "filters/") !== false) {
    include_once __DIR__."/autoload.php";

    if (!class_exists('DLTemplate')) {
        include_once (MODX_BASE_PATH . 'assets/snippets/DocLister/lib/DLTemplate.class.php');
    }

    $controller = new \Filters\Controllers\CoreController();
    $page = $controller->run();

    if ($page) {
        die($page);
    }
}
