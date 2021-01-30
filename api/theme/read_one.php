<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// подключение файла для соединения с базой и файл с объектом 
include_once '../config/database.php';
include_once '../objects/Theme.php';

// получаем соединение с базой данных 
$database = new Database();
$db = $database->getConnection();

// подготовка объекта 
$theme = new Theme($db);

// установим свойство ID записи для чтения 
$theme->id = isset($_GET['id']) ? $_GET['id'] : die();

// прочитаем детали товара для редактирования 
$theme->readOne();

if ($theme->name!=null) {

    // создание массива 
    $theme_arr = array(
        "id" =>  $theme->id,
        "name" => $theme->name,
        "filepatch" => $theme->filepatch,
        "section_id" => $theme->section_id,
        "section_name" => $theme->section_name
    );

    // код ответа - 200 OK 
    http_response_code(200);

    // вывод в формате json 
    echo json_encode($theme_arr);
}

else {
    // код ответа - 404 Не найдено 
    http_response_code(404);

    // сообщим пользователю, что товар не существует 
    echo json_encode(array("message" => "Тема не существует."), JSON_UNESCAPED_UNICODE);
}
?>