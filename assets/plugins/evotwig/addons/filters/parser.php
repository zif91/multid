<?php

return new \Twig\TwigFilter('parser',
    function ($out) {
        $modx = evolutionCMS();

        $modx->minParserPasses = 2;
        $modx->maxParserPasses = 10;

        $out = $modx->parseDocumentSource($out, $modx);

        $modx->minParserPasses = -1;
        $modx->maxParserPasses = -1;

        return $out;
    }
);