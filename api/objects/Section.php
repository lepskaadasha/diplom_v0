<?php
class Section {

    // соединение с БД и таблицей 'section' 
    private $conn;
    private $table_name = "section";

    // свойства объекта 
    public $id;
    public $name;
    public $created;

    public function __construct($db){
        $this->conn = $db;
    }

    // используем раскрывающийся список выбора 
    public function readAll(){
        // выборка всех данных 
        $query = "SELECT
                    id, name
                FROM
                    " . $this->table_name . "
                ORDER BY
                    name";
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }
    // используем раскрывающийся список выбора 
    public function read(){

        // выбираем все данные 
        $query = "SELECT
                    id, name
                FROM
                    " . $this->table_name . "
                ORDER BY
                    name";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        return $stmt;
    }
    function create(){

        // запрос для вставки (создания) записей 
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    name=:name, created=:created";

        // подготовка запроса 
        $stmt = $this->conn->prepare($query);

        // очистка 
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->created=htmlspecialchars(strip_tags($this->created));

        // привязка значений 
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":created", $this->created);

        // выполняем запрос 
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    function update(){

        // запрос для обновления записи (темы) 
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    name = :name
                WHERE
                    id = :id";

        // подготовка запроса 
        $stmt = $this->conn->prepare($query);

        // очистка 
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->id=htmlspecialchars(strip_tags($this->id));

        // привязываем значения 
        $stmt->bindParam(':name', $this->name);
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
                   *
                FROM
                   section
                WHERE
                    name LIKE ?
                ORDER BY
                    created DESC";

        // подготовка запроса 
        $stmt = $this->conn->prepare($query);

        // очистка 
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        // привязка 
        $stmt->bindParam(1, $keywords);

        // выполняем запрос 
        $stmt->execute();

        return $stmt;
    }
}
?>