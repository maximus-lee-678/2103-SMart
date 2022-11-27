<?php

require 'vendor/autoload.php';

$config = parse_ini_file('../../private/mongo-config.ini');
$client = new MongoDB\Client("mongodb://" . $config['username'] . ":" . $config['password'] . "@localhost:27017");
$db = $client->SMart;

$custQuery = array('id' => 1010);
$result = $db->Customer->find($custQuery)->toArray()[0];

$bol = $result["payment_info"]->count();

print_r($bol)
?>
