<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение необходимых файлов 
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/Section.php';

// создание подключения к БД 
$database = new Database();
$db = $database->getConnection();

// инициализируем объект 
$section = new Section($db);

// получаем ключевые слова 
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

$stmt = $section->search($keywords);
$num = $stmt->rowCount();

// проверяем, найдено ли больше 0 записей 
if ($num>0) {


    $sections_arr=array();
    $sections_arr["records"]=array();

    // получаем содержимое нашей таблицы 
    // fetch() быстрее чем fetchAll() 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // извлечём строку 
        extract($row);

        $section_item=array(
            "id" => $id,
            "name" => $name,
        );

        array_push($sections_arr["records"], $section_item);
    }

    // код ответа - 200 OK 
    http_response_code(200);

    // покажем
    echo json_encode($sections_arr);
}

else {
    // код ответа - 404 Ничего не найдено 
    http_response_code(404);

    // скажем пользователю, что не найдены 
    echo json_encode(array("message" => "Раздел не найдены."), JSON_UNESCAPED_UNICODE);
}
?>