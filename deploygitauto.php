<?php
$dir = '/home/jacov9sw.beget.tech/public_html';

// Выполнение команды и сохранение вывода и ошибок в переменной $output
//exec("cd $dir && git pull 2>&1", $output, $return_var);

// Вывод содержимого массива $output и кода возврата
//var_dump($output);
//echo "<br>";
//echo "Код возврата: $return_var";
public function gitPull() {
    try {
        exec("cd $dir && git pull 2>&1", $output);
        Log::info($output);
    } catch (\Exception $e) {
        Log::error($e);
    }
    http_response_code(200);
}
var_dump($output);
var_dump($e);