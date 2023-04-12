<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header('Access-Control-Allow-Credentials', true);
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
      http_response_code(405);
      echo json_encode([
          'success' => 0,
          'message' => 'Bad Request Detected.'
      ]);
      exit;
}

require_once 'db_connect.php';

$database = new Operations();
$conn = $database->dbConnection();

try{

      $titre = str_replace("\"", "`",str_replace("\'", "\'\'",$_POST['titre']));
      $description = str_replace("\"", "`",str_replace("\'", "\'\'",$_POST['description']));
      $lien = $_POST['lien'];
      $file = $_FILES['image'];

      $sql='INSERT INTO pagesconnexes (titreConnexe, descriptionConnexe, lienConnexe)'
            . 'VALUES ("'.$titre.'", "'.$description.'", "'.$lien.'")';
      $exec=$conn->prepare($sql);
      $exec->execute();

      $sql='SELECT idConnexe FROM pagesconnexes WHERE lienConnexe = "'.$lien.'"';
      $exec=$conn->prepare($sql);
      $exec->execute();
      $idP = $exec->fetch(PDO::FETCH_ASSOC);

      $idP = $idP['idConnexe'];

      $tmp_name = $file['tmp_name'];
      $name = $file['name'];

      $file_ext = explode('.', $name);
      $file_ext = strtolower(end($file_ext));

      $name = 'page-'. $idP . '.' .$file_ext;

      move_uploaded_file($tmp_name, "photos/pagesConnexes/$name");
      
      $sql="UPDATE pagesconnexes SET imageConnexe = '$name' WHERE idConnexe = '$idp'";
      $exec=$conn->prepare($sql);
      $exec->execute();

      echo json_encode([
          'success' => 1,
          'message' => 'Page ajoutée avec succès.',
          'idP' => $idP,
          'name' => $name


      ]);

}catch (Exception $e) {
      http_response_code(404);
      echo json_encode([
          'success' => 0,
          'data' => $e->getMessage()
      ]);
      exit;
}
?>