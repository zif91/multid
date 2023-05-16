<?php

$twig = new Twig_Environment($loader);
$func = new Twig_Function('randomPhoto', function () {
    return 'Тест';
});

$twig->addFunction($func);

