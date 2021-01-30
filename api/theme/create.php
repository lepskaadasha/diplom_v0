<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// получаем соединение с базой данных 
include_once '../config/database.php';

// создание объекта товара 
include_once '../objects/Theme.php';

$database = new Database();
$db = $database->getConnection();

$theme = new Theme($db);
 
// получаем отправленные данные 
$data = json_decode(file_get_contents("php://input"));
 
echo $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
echo `<script>alert($ext)</script>`;
$uniqid = uniqid();
$filename = $uniqid.".".$ext;
if(isset($_FILES) && $_FILES['file']['error'] == 0 && $_FILES['file']['name'] != ""){ 
    $destiation_dir = "../pdf/$filename"; // Директория для размещения файла
    move_uploaded_file($_FILES['file']['tmp_name'], $destiation_dir); // Перемещаем файл в желаемую директорию
    }
    else {
        echo json_encode(array("message" => "'Файл не загружен. Проверьте целостность файла"), JSON_UNESCAPED_UNICODE);
    }

// убеждаемся, что данные не пусты 
if (
    !empty($data->name) &&
    !empty($data->filepatch) &&
    !empty($data->section_id)
) {

    $filepatch = 'pdf/'.$filename;
    // устанавливаем значения свойств товара 
    $theme->name = $data->name;
    $theme->filepatch = $data->filepatch;
    $theme->section_id = $data->section_id;
    $theme->created = date('Y-m-d H:i:s');

    // создание товара 
    if($theme->create()){

        // установим код ответа - 201 создано 
        http_response_code(201);

        // сообщим пользователю 
        echo json_encode(array("message" => "Тема была создан."), JSON_UNESCAPED_UNICODE);
    }

    // если не удается создать товар, сообщим пользователю 
    else {

        // установим код ответа - 503 сервис недоступен 
        http_response_code(503);

        // сообщим пользователю 
        echo json_encode(array("message" => "Невозможно создать тему."), JSON_UNESCAPED_UNICODE);
    }
}

// сообщим пользователю что данные неполные 
else {

    // установим код ответа - 400 неверный запрос 
    http_response_code(400);

    // сообщим пользователю 
    echo json_encode(array("message" => "Невозможно создать тему. Данные неполные."), JSON_UNESCAPED_UNICODE);
}
?>