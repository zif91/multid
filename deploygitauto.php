<?php
$dir = 'jacov9sw.beget.tech/public_html';
exec("cd $dir && git pull 2>&1", $output);
echo $output;
//test autoload