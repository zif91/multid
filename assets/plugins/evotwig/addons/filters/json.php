<?php

return new \Twig\TwigFilter('json',
    function ($json) {
        return json_decode($json, true);
    }
);
