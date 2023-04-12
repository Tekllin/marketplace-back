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

      $nom = str_replace("\"", "`",str_replace("\'", "\'\'",$_POST['nom']));
      $prenom = str_replace("\"", "`",str_replace("\'", "\'\'",$_POST['prenom']));
      $adresse = str_replace("\"", "`",str_replace("\'", "\'\'",$_POST['adresse']));
      $ville = str_replace("\"", "`",str_replace("\'", "\'\'",$_POST['ville']));
      $pays = str_replace("\"", "`",str_replace("\'", "\'\'",$_POST['pays']));
      $cp = str_replace("\"", "`",str_replace("\'", "\'\'",$_POST['cp']));
      $tel = $_POST['tel'];
      $email = $_POST['email'];
      $nomSociete = str_replace("\"", "`",str_replace("\'", "\'\'",$_POST['nomSociete']));
      $siret = $_POST['siret'];
      $mdp = $_POST['mdp'];
      $newsletter = $_POST['newsletter'];
      $motiv = str_replace("\"", "`",str_replace("\'", "\'\'",$_POST['motiv']));
      $file = $_FILES['image'];

      $sql='INSERT INTO user (nomUser, prenomUser, emailUser, passwordUser, telUser, idDroitUser, newsUser, idCoefPointsCredit)'
            . 'VALUES ("'.$nom.'", "'.$prenom.'", "'.$email.'", "'.$mdp.'", "'.$tel.'", 4, "'.$newsletter.'", 1)';
      $exec=$conn->prepare($sql);
      $exec->execute();
      
      $sql='INSERT INTO partenaires (idPartenaire, nomSociete, adresseSociete, villeSociete, cpSociete, pays, siret, idEtatPartenaire, titreFiche, descriptionFiche, motivPartenaire)'
            . 'VALUES ((SELECT idUser FROM user WHERE emailUser = "'.$email.'" AND telUser = "'.$tel.'"), "'.$nomSociete.'", "'.$adresse.'", "'.$ville.'", "'.$cp.'", "'.$pays.'", "'.$siret.'", 1, "", "", "'.$motiv.'")';
      $exec=$conn->prepare($sql);
      $exec->execute();

      $sql="SELECT idPartenaire FROM partenaires WHERE idPartenaire = (SELECT idUser FROM user WHERE emailUser = '$email' AND telUser = '$tel')";
      $exec=$conn->prepare($sql);
      $exec->execute();
      $idP = $exec->fetch();
      $idP = $idP['idPartenaire'];

      
      $tmp_name = $file['tmp_name'];
      $name = $file['name'];

      $file_ext = explode('.', $name);
      $file_ext = strtolower(end($file_ext));

      $name = 'logo-'. $idP . '.' .$file_ext;

      move_uploaded_file($tmp_name, "photos/logoPartenaires/$name");

      $sql="UPDATE partenaires SET logo = '$name' WHERE idPartenaire = $idP";
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