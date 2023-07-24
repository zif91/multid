<?php
define('MODX_API_MODE', true);
include_once(dirname(FILE) . "/index.php");

#$dir = '/home/jacov9sw.beget.tech/public_html';
$dir = '/home/j/jacov9sw/jacov9sw.beget.tech/public_html';
putenv("HOME=/home/j/jacov9sw/jacov9sw.beget.tech");
echo ("<pre>");
try {
    exec("cd $dir && git pull -v 2>&1", $output);
    var_dump($output);
} catch (\Exception $e) {
    var_dump($e);
}
http_response_code(200);
$modx->clearCache('full');   
echo ("</pre>");
echo("success");
?>