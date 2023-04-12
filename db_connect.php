<?php

class Operations {
    private $db_host = 'localhost';
    private $db_name = 'marketplace';
    private $db_username = 'root';
    private $db_password = '';

    public function dbConnection() {
        
        try {
            $conn = new PDO('mysql:host='. $this->db_host .';dbname='.
            $this->db_name, $this->db_username, $this->db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        }
    }

    public function checkToken($x) {
        $conn = $this->dbConnection();
        $sql = "SELECT idUser FROM user WHERE tokenConn ='$x'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result) {
            return true;
        } else {
            return false;
        }
    }
}

?>

