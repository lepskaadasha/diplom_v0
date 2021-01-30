<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключаем файл для работы с БД и объектом theme 
include_once '../config/database.php';
include_once '../objects/Theme.php';

// получаем соединение с базой данных 
$database = new Database();
$db = $database->getConnection();

// подготовка объекта 
$theme = new Theme($db);

// получаем id товара для редактирования 
$data = json_decode(file_get_contents("php://input"));

// установим id свойства товара для редактирования 
$theme->id = $data->id;

// установим значения свойств товара 
$theme->name = $data->name;
$theme->filepatch = $data->filepatch;
$theme->section_id = $data->section_id;

// обновление товара 
if ($theme->update()) {

    // установим код ответа - 200 ok 
    http_response_code(200);

    // сообщим пользователю 
    echo json_encode(array("message" => "Тема был обновлён."), JSON_UNESCAPED_UNICODE);
}

// если не удается обновить товар, сообщим пользователю 
else {

    // код ответа - 503 Сервис не доступен 
    http_response_code(503);

    // сообщение пользователю 
    echo json_encode(array("message" => "Невозможно обновить тему."), JSON_UNESCAPED_UNICODE);
}
?>