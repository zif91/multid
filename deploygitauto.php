<?php
$result = exec('whoami');
var_dump($result);
echo "<br>";
$dir = 'jacov9sw.beget.tech/public_html';
exec("git config --global safe.directory '*' && git pull 2>&1", $output);
var_dump($output);

exec("cd $dir && git pull 2>&1", $output);
var_dump($output);

//test autoload  123