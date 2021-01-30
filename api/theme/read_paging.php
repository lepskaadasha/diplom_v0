<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение файлов 
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/Theme.php';

// utilities 
$utilities = new Utilities();

// создание подключения 
$database = new Database();
$db = $database->getConnection();

// инициализация объекта 
$theme = new Theme($db);


$stmt = $theme->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// если больше 0 записей 
if ($num>0) {


    $themes_arr=array();
    $themes_arr["records"]=array();
    $themes_arr["paging"]=array();

    // получаем содержимое нашей таблицы 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // извлечение строки 
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

    // подключим пагинацию 
    $total_rows=$theme->count();
    $page_url="{$home_url}theme/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $themes_arr["paging"]=$paging;

    // установим код ответа - 200 OK 
    http_response_code(200);

    // вывод в json-формате 
    echo json_encode($themes_arr);
} else {

    // код ответа - 404 Ничего не найдено 
    http_response_code(404);

    // сообщим пользователю, что тем не существует 
    echo json_encode(array("message" => "Темы не найдены."), JSON_UNESCAPED_UNICODE);
}
?>