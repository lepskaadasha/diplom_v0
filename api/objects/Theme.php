<?php
class Theme {

    // подключение к базе данных и таблице 'theme' 
    private $conn;
    private $table_name = "themes";

    // свойства объекта 
    public $id;
    public $name;
    public $filepatch;
    public $section_id;
    public $section_name;
    public $created;

    // конструктор для соединения с базой данных 
    public function __construct($db){
        $this->conn = $db;
    }

    // метод read() - получение темы 
    function read(){

        // выбираем все записи 
        $query = "SELECT
                    c.name as section_name, p.id, p.name, p.filepatch, p.section_id, p.created
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        section c
                            ON p.section_id = c.id
                ORDER BY
                    p.created DESC";

        // подготовка запроса 
        $stmt = $this->conn->prepare($query);

        // выполняем запрос 
        $stmt->execute();

        return $stmt;
    }
    // метод create - создание темы 
    function create(){

        // запрос для вставки (создания) записей 
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    name=:name, filepatch=:filepatch, section_id=:section_id, created=:created";

        // подготовка запроса 
        $stmt = $this->conn->prepare($query);

        // очистка 
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->filepatch=htmlspecialchars(strip_tags($this->filepatch));
        $this->section_id=htmlspecialchars(strip_tags($this->section_id));
        $this->created=htmlspecialchars(strip_tags($this->created));

        // привязка значений 
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":filepatch", $this->filepatch);
        $stmt->bindParam(":section_id", $this->section_id);
        $stmt->bindParam(":created", $this->created);

        // выполняем запрос 
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    // используется при заполнении формы обновления темы 
    function readOne() {

        // запрос для чтения одной записи (темы) 
        $query = "SELECT
                    c.name as section_name, p.id, p.name, p.filepatch, p.section_id, p.created
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        section c
                            ON p.section_id = c.id
                WHERE
                    p.id = ?
                LIMIT
                    0,1";

        // подготовка запроса 
        $stmt = $this->conn->prepare( $query );

        // привязываем id темы, который будет обновлен 
        $stmt->bindParam(1, $this->id);

        // выполняем запрос 
        $stmt->execute();

        // получаем извлеченную строку 
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // установим значения свойств объекта 
        $this->name = $row['name'];
        $this->filepatch = $row['filepatch'];
        $this->section_id = $row['section_id'];
        $this->section_name = $row['section_name'];
    }
    // метод update() - обновление темы 
    function update(){

        // запрос для обновления записи (темы) 
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    name = :name,
                    price = :price,
                    filepatch = :filepatch,
                    section_id = :section_id
                WHERE
                    id = :id";

        // подготовка запроса 
        $stmt = $this->conn->prepare($query);

        // очистка 
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->filepatch=htmlspecialchars(strip_tags($this->filepatch));
        $this->section_id=htmlspecialchars(strip_tags($this->section_id));
        $this->id=htmlspecialchars(strip_tags($this->id));

        // привязываем значения 
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':filepatch', $this->filepatch);
        $stmt->bindParam(':section_id', $this->section_id);
        $stmt->bindParam(':id', $this->id);

        // выполняем запрос 
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    // метод delete - удаление товара 
    function delete(){

        // запрос для удаления записи (товара) 
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // подготовка запроса 
        $stmt = $this->conn->prepare($query);

        // очистка 
        $this->id=htmlspecialchars(strip_tags($this->id));

        // привязываем id записи для удаления 
        $stmt->bindParam(1, $this->id);

        // выполняем запрос 
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
  
    function search($keywords){

        // выборка по всем записям 
        $query = "SELECT
                    c.name as section_name, p.id, p.name, p.filepatch, p.section_id, p.created
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        section c
                            ON p.section_id = c.id
                WHERE
                    p.name LIKE ? OR p.filepatch LIKE ? OR c.name LIKE ?
                ORDER BY
                    p.created DESC";

        // подготовка запроса 
        $stmt = $this->conn->prepare($query);

        // очистка 
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        // привязка 
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        // выполняем запрос 
        $stmt->execute();

        return $stmt;
    }

    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    public function readPaging($from_record_num, $records_per_page){

        // выборка 
        $query = "SELECT
                    c.name as section_name, p.id, p.name, p.filepatch, p.section_id, p.created
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        section c
                            ON p.section_id = c.id
                ORDER BY p.created DESC
                LIMIT ?, ?";

        // подготовка запроса 
        $stmt = $this->conn->prepare( $query );

        // свяжем значения переменных 
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        // выполняем запрос 
        $stmt->execute();

        // вернём значения из базы данных 
        return $stmt;
    }
}
?>