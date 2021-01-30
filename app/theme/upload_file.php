<?php
session_start();
$adresserver = 'localhost';
$nameuser='root';
$password='';
$namebd='study_tutorial';
$link=mysqli_connect($adresserver, $nameuser, $password) or die('Ошибка'.mysql_error($link));
mysqli_select_db($link,$namebd) or die('Не могу подключиться');
$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$uniqid = uniqid();
$filename = $uniqid.".".$ext;
if(isset($_FILES) && $_FILES['file']['error'] == 0 && $_FILES['file']['name'] != ""){ 
    $destiation_dir = "E:/Programm/OSPanel/domains/diplom/pdf/$filename"; // Директория для размещения файла
    move_uploaded_file($_FILES['file']['tmp_name'], $destiation_dir); // Перемещаем файл в желаемую директорию
    $posts=mysqli_query($link, "INSERT INTO themes(name, section_id, filepatch) VALUES('$_POST[name]', '$_POST[section_id]', '/pdf/$filename')");

    http_response_code(200);
    echo "Тема успешно добавлена";
    }
    else {
        http_response_code(405); 
        echo 'Файл не загружен. Проверьте целостность файла.';
    }
?>