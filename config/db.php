<?php


$host = 'localhost';
$db = 'root';
$pass = 'Zech12345@';
$name = 'servisis_db';

$connected = mysqli_connect($host, $db, $pass, $name);


if ($connected) {
} else {
    printf("hgahaha");
}
