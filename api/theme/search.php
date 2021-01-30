<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение необходимых файлов 
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/Theme.php';

// создание подключения к БД 
$database = new Database();
$db = $database->getConnection();

// инициализируем объект 
$theme = new Theme($db);

// получаем ключевые слова 
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

$stmt = $theme->search($keywords);
$num = $stmt->rowCount();

// проверяем, найдено ли больше 0 записей 
if ($num>0) {


    $themes_arr=array();
    $themes_arr["records"]=array();

    // получаем содержимое нашей таблицы 
    // fetch() быстрее чем fetchAll() 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // извлечём строку 
        extract($row);

        $theme_item=array(
            "id" => $id,
            "name" => $name,
            "filepatch" => html_entity_decode($filepatch),
            "section_id" => $section_id,
            "section_name" => $section_name
        );

        array_push($themes_arr["records"], $theme_item);
    }

    // код ответа - 200 OK 
    http_response_code(200);

    // покажем
    echo json_encode($themes_arr);
}

else {
    // код ответа - 404 Ничего не найдено 
    http_response_code(404);

    // скажем пользователю, что не найдены 
    echo json_encode(array("message" => "Тема не найдены."), JSON_UNESCAPED_UNICODE);
}
?>