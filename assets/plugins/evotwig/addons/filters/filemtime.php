<?php

return new \Twig\TwigFilter('filemtime',
    function ($file) {
        return $file . '?v=' . filemtime(MODX_BASE_PATH . trim($file, '/'));
    }
);