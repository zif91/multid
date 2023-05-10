<?php

return new \Twig\TwigFilter('url',
    function ($id, $absolute = false) {
        return $this->modx->makeUrl($id, '', http_build_query([]), $absolute ? 'full' : '');
    }
);
