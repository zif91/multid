<?php
$loader = function($class) {
    static $classes = null;
    if ($classes === null) {
        $classes = array(
            'Filters\\Controllers\\CoreController' => '/Controllers/CoreController.php',
        );
    }
    if (isset($classes[$class])) {
        require dirname(__FILE__) . $classes[$class];
    }
};
spl_autoload_register($loader, true);
