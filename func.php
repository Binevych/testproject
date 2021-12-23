<?php
$host = '127.0.0.1';
$user = 'root';
$password = '';
$database = 'testproject';

$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка ".mysqli_error($link));

mysqli_set_charset($link, 'utf8');

?>