<?php

return new \Twig\TwigFilter('phone',
    function ($number, $format = '8 (XXX) XXX-XX-XX', $link = false) {
        if (!empty($number)) {
            $format = mb_strtoupper($format, evolutionCMS()->config['modx_charset']);
            $i = $format[0] == 'X' || $format[0] == '+' && $format[1] != '7' ? 0 : 1;
            $class = !empty($class) ? ' class="' . $class . '"' : '';

            $format = str_replace('X', '|X|', $format);
            $format = explode('|', $format);
            $_number = preg_replace('/[^0-9]/', '', $number);
            foreach($format as $k => $v) {
                if ($v == 'X' && isset($_number[$i])) {
                    $format[$k] = $_number[$i];
                    $i++;
                }
            }
            $format = implode('', $format);
            if (!empty($link)) {
                $format = '<a href="tel:' . $number . '"' . $class . '>' . $format . '</a>';
            }

            return $format;
        }
    }
);