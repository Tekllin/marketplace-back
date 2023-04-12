<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header('Access-Control-Allow-Credentials', true);
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Bad Request detected! Only POST method is allowed',
    ]);
    exit;
}

require_once 'db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();

$data = file_get_contents("php://input");

try {
      $id = $_POST["id"];
      $titre = $_POST["titre"];
      $description = $_POST["description"];

      $titre = str_replace("\"", "`",str_replace("\'", "\'\'", $titre));
      $description = str_replace("\"", "`",str_replace("\'", "\'\'", $description));

      $sql = 'UPDATE partenaires set titreFiche = "'.$titre.'" WHERE idPartenaire = '.$id;
      $exec=$conn->prepare($sql);
      $exec->execute();

      $sql = 'UPDATE partenaires set descriptionFiche = "'.$description.'" WHERE idPartenaire = '.$id;
      $exec=$conn->prepare($sql);
      $exec->execute();
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
    exit;
}
?>