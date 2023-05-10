<?php


/* Use config.php to override plugin settings */
return [
    'templatesPath' => 'templates/'.$modx->getPlaceholder('template_name').'/',
    'templatesExtension' => 'twig',
    'debug' => true,
    'disableTwig' => false,
    'allowedFilters' => 'count,filesize,get_key,intval,json_encode,md5,round,implode,is_numeric,stripos,explode,mb_strtolower,dump', // list of allowed filters
];

$twig->addFilter(new \Twig\TwigFilter('dump', 'dump'));
