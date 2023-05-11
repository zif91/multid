<?php
$dir = '/home/jacov9sw.beget.tech/public_html';

try {
    exec("cd $dir && git pull 2>&1", $output);
    var_dump($output);
} catch (\Exception $e) {
    var_dump($e);
}
http_response_code(200);
?>
