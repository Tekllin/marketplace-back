<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: POST");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == "OPTIONS") 
    {
        die();
    }

    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') :
        http_response_code(405);
        echo json_encode([
            'success' => 0,
            'message' => 'Bad Request!.Only POST method is allowed',
        ]);
        exit;
    endif;
    
    require '../../db_connect.php';
    $db = new Operations();
    $cnx = $db->dbConnection();
    
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->idPartenaire) || !isset($data->libelleNomPartenaire) || !isset($data->adressePartenaire) || !isset($data->villePartenaire) || !isset($data->cpPartenaire) || !isset($data->paysPartenaire) || !isset($data->logoPartenaire) || !isset($data->siret) || !isset($data->motivPartenaire)) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Please enter compulsory fileds | idPartenaire, libelleNomPartenaire, adressePartenaire, villePartenaire, cpPartenaire, paysPartenaire, siret, motivPartenaire and logoPartenaire',
        ]);
        exit;
    
    elseif (empty(trim($data->idPartenaire)) || empty(trim($data->libelleNomPartenaire)) ||empty(trim($data->adressePartenaire)) || empty(trim($data->villePartenaire)) || empty(trim($data->cpPartenaire)) || empty(trim($data->paysPartenaire)) || empty(trim($data->logoPartenaire)) || empty(trim($data->siret)) || empty(trim($data->motivPartenaire))) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Field cannot be empty. Please fill all the fields.',
        ]);
        exit;
    
    endif;
    
    try 
    {
        $idPartenaire = htmlspecialchars(trim($data->idPartenaire));
        $libelleNomPartenaire = htmlspecialchars(trim($data->libelleNomPartenaire));
        $adressePartenaire = htmlspecialchars(trim($data->adressePartenaire));
        $villePartenaire = htmlspecialchars(trim($data->villePartenaire));
        $cpPartenaire = htmlspecialchars(trim($data->cpPartenaire));
        $paysPartenaire = htmlspecialchars(trim($data->paysPartenaire));
        $logoPartenaire = htmlspecialchars(trim($data->logoPartenaire));
        $siret = htmlspecialchars(trim($data->siret));
        $motivPartenaire = htmlspecialchars(trim($data->motivPartenaire));

        $query = "INSERT INTO `partenaires`(idPartenaire,nomSociete,adresseSociete,villeSociete,cpSociete,pays,logo,siret,motivPartenaire,idEtatPartenaire)
        VALUES(:idPartenaire,:libelleNomPartenaire,:adressePartenaire,:villePartenaire,:cpPartenaire,:paysPartenaire,:logoPartenaire,:siret,:motivPartenaire,1)";
    
        $stmt = $cnx->prepare($query);
        
        $stmt->bindValue(':idPartenaire', $idPartenaire, PDO::PARAM_INT);
        $stmt->bindValue(':libelleNomPartenaire', $libelleNomPartenaire, PDO::PARAM_STR);
        $stmt->bindValue(':adressePartenaire', $adressePartenaire, PDO::PARAM_STR);
        $stmt->bindValue(':villePartenaire', $villePartenaire, PDO::PARAM_STR);
        $stmt->bindValue(':cpPartenaire', $cpPartenaire, PDO::PARAM_STR);
        $stmt->bindValue(':paysPartenaire', $paysPartenaire, PDO::PARAM_STR);
        $stmt->bindValue(':logoPartenaire', $logoPartenaire, PDO::PARAM_STR);
        $stmt->bindValue(':siret', $siret, PDO::PARAM_STR);
        $stmt->bindValue(':motivPartenaire', $motivPartenaire, PDO::PARAM_STR);

        if ($stmt->execute()) {
        
            http_response_code(201);
            echo json_encode([
                'success' => 1,
                'message' => 'Data Inserted Successfully.'
            ]);
            exit;
        }

        echo json_encode([
            'success' => 0,
            'message' => 'There is some problem in data inserting'
        ]);
        exit;
    
    } 
    catch (PDOException $e) 
    {
        http_response_code(500);
        echo json_encode([
            'success' => 0,
            'message' => $e->getMessage()
        ]);
        exit;
    }
?>