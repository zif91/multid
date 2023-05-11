<?php
$dir = '/home/jacov9sw.beget.tech/public_html';

try {
    exec("cd $dir && git pull 2>&1", $output);
    Log::info($output);
    var_dump($output);
} catch (\Exception $e) {
    Log::error($e);
    var_dump($e);
}
http_response_code(200);


var_dump($output);
var_dump($e);
?>
