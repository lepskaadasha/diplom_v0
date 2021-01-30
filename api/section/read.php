<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение файлов для соединения с БД и файл с объектом Section 
include_once '../config/database.php';
include_once '../objects/section.php';

// создание подключения к базе данных 
$database = new Database();
$db = $database->getConnection();

// инициализация объекта 
$section = new Section($db);

// запрос для категорий 
$stmt = $section->read();
$num = $stmt->rowCount();

// проверяем, найдено ли больше 0 записей 
if ($num>0) {

    // массив 
    $categories_arr=array();
    $categories_arr["records"]=array();

    // получим содержимое нашей таблицы 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // извлекаем строку 
        extract($row);

        $section_item=array(
            "id" => $id,
            "name" => $name,
        );

        array_push($categories_arr["records"], $section_item);
    }

    // код ответа - 200 OK 
    http_response_code(200);

    // покажем данные категорий в формате json 
    echo json_encode($categories_arr);
} else {

    // код ответа - 404 Ничего не найдено 
    http_response_code(404);

    // сообщим пользователю, что категории не найдены 
    echo json_encode(array("message" => "Категории не найдены."), JSON_UNESCAPED_UNICODE);
}
?>