<?php
$ext = $_GET['ext'];
$number = $_GET['number'];
$api = new Extension;

if (empty($ext)) {
  exit('Define an extension');
}

if (empty($number)) {
  exit('Define a number');
}

$response = $api->getCallerInExtension($ext, $number);
$data = json_decode($response->getBody()->getContents(), 1);
new dBug($data);