<?php

header('Content-type:application/json;charset=utf-8');

$image_data[0] = [0, 0, 0, 0, 0];
$image_data[1] = [0, 1, 1, 1, 0];
$image_data[2] = [1, 0, 1, 0, 1];
$image_data[3] = [1, 0, 1, 0, 1];
$image_data[4] = [0, 0, 1, 0, 1];
$image_data[5] = [0, 1, 1, 1, 0];
$image_data[6] = [0, 0, 1, 0, 0];
$image_data[9] = [0, 0, 1, 0, 0];

$json = json_encode($image_data, 1);
echo $json;

?>