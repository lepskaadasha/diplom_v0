<?php
// установить часовой пояс по умолчанию 
date_default_timezone_set('Europe/Minsk');
 
// переменные, используемые для JWT 
$key = "ESTj2133dhgv54byuTU4ykbhi&65Iuh";  // secret key
$iss = "http://any-site.org";
$aud = "http://any-site.com";
$iat = 1356999524;
$nbf = 1357000000;

// показывать сообщения об ошибках 
ini_set('display_errors', 1);
error_reporting(E_ALL);

// URL домашней страницы 
$home_url="http://diplom/api/";

// страница указана в параметре URL, страница по умолчанию одна 
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// установка количества записей на странице 
$records_per_page = 5;

// расчёт для запроса предела записей 
$from_record_num = ($records_per_page * $page) - $records_per_page;
?>