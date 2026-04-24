<?php
$json = file_get_contents('http://127.0.0.1:1234/v1/models');
if ($json === false) {
    echo 'ERROR';
    exit(1);
}
$data = json_decode($json, true);
if (! isset($data['data']) || ! is_array($data['data'])) {
    echo 'INVALID RESPONSE' . PHP_EOL;
    var_export($data);
    exit(1);
}
foreach ($data['data'] as $model) {
    echo $model['id'] . PHP_EOL;
}
