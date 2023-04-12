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

$data = file_get_contents("php://input");
if(!isset($data->nom) || !isset($data->prenom) || !isset($data->adresse) || !isset($data->ville) || !isset($data->pays) || !isset($data->cp)|| !isset($data->tel)|| !isset($data->email)|| !isset($data->nomSociete)|| !isset($data->mdp)){
    echo json_encode([
        'success' => 0,
        'message' => 'Enter all values.'
    ]);
}

try{
      $nom = $_POST['nom'];
      $prenom = $_POST['prenom'];
      $adresse = $_POST['adresse'];
      $ville = $_POST['ville'];
      $pays = $_POST['pays'];
      $cp = $_POST['cp'];
      $tel = $_POST['tel'];
      $email = $_POST['email'];
      $nomAnnonceur = $_POST['nomSociete'];
      $siret = $_POST['siret'];
      $mdp = $_POST['mdp'];
      $newsletter = $_POST['newsletter'];
      $file = $_FILES['image'];

      $sql="INSERT INTO user (nomUser, prenomUser, emailUser, passwordUser, telUser, idDroitUser, newsUser)"
            . "VALUES ('$nom', '$prenom', '$email', '$mdp', '$tel', 6, '$newsletter')";
      $exec=$conn->prepare($sql);
      $exec->execute();

      $sql="SELECT idUser FROM user WHERE emailUser = '$email' AND telUser = '$tel'";
      $exec=$conn->prepare($sql);
      $exec->execute();
      $idA = $exec->fetch();
      $idA = $idA['idUser'];
      
      $sql="INSERT INTO annonceurs (idAnnonceur, libelleNomAnnonceur, adresseAnnonceur, villeAnnonceur, cpAnnonceur, paysAnnonceur, siretAnnonceur, idEtat)"
            . "VALUES ($idA, '$nomAnnonceur', '$adresse', '$ville', '$cp', '$pays', '$siret', 1)";
      $exec=$conn->prepare($sql);
      $exec->execute();
      
      $tmp_name = $file['tmp_name'];
      $name = $file['name'];

      $file_ext = explode('.', $name);
      $file_ext = strtolower(end($file_ext));

      $name = 'logo-'. $idA . '.' .$file_ext;

      move_uploaded_file($tmp_name, "photos/logoAnnonceurs/$name");

      $sql="UPDATE annonceurs SET logoAnnonceur = '$name' WHERE idAnnonceur = $idA";
      $exec=$conn->prepare($sql);
      $exec->execute();

}catch (Exception $e) {
      http_response_code(404);
      echo json_encode([
          'success' => 0,
          'data' => $e->getMessage()
      ]);
      exit;
}
?>