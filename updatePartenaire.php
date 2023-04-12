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
      $id = $_POST['id'];
      $nom = str_replace("\"", "`",str_replace("\'", "\'\'", $_POST['nom']));
      $prenom = str_replace("\"", "`",str_replace("\'", "\'\'", $_POST['prenom']));
      $adresse = str_replace("\"", "`",str_replace("\'", "\'\'", $_POST['adresse']));
      $ville = str_replace("\"", "`",str_replace("\'", "\'\'", $_POST['ville']));
      $pays = str_replace("\"", "`",str_replace("\'", "\'\'", $_POST['pays']));
      $cp = str_replace("\"", "`",str_replace("\'", "\'\'", $_POST['cp']));
      $tel = $_POST['tel'];
      $email = $_POST['email'];
      $nomSociete = str_replace("\"", "`",str_replace("\'", "\'\'", $_POST['nomSociete']));
      $siret = $_POST['siret'];
      if(isset($_FILES['image'])){
        $file = $_FILES['image'];

        $tmp_name = $file['tmp_name'];
        $name = $file['name'];

        $file_ext = explode('.', $name);
        $file_ext = strtolower(end($file_ext));

        $name = 'logo-'. $id . '.' .$file_ext;

        move_uploaded_file($tmp_name, "photos/logoPartenaires/$name");
      }

      $sql = 'UPDATE user SET nomUser = "'.$nom.'", prenomUser = "'.$prenom.'", emailUser = "'.$email.'", telUser = "'.$tel.'" WHERE idUser = '.$id;
      $exec=$conn->prepare($sql);
      $exec->execute();

      $sql = 'UPDATE partenaires SET nomSociete = "'.$nomSociete.'", adresseSociete = "'.$adresse.'", villeSociete = "'.$ville.'", cpSociete = "'.$cp.'", pays = "'.$pays.'", siret = "'.$siret.'" WHERE idPartenaire = '.$id;
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