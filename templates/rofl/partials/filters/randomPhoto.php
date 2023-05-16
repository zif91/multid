<?php

function randomPhoto() {
    return 'Тест';
}

$twig = new Twig_Environment($loader);
$func = new Twig_Function('randomPhoto', randomPhoto());

$twig->addFunction($func);

