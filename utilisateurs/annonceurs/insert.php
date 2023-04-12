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

    if (!isset($data->idAnnonceur) || !isset($data->libelleNomAnnonceur) || !isset($data->adresseAnnonceur) || !isset($data->villeAnnonceur) || !isset($data->cpAnnonceur) || !isset($data->paysAnnonceur) || !isset($data->logoAnnonceur) || !isset($data->siretAnnonceur) || !isset($data->motivAnnonceur)) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Please enter compulsory fileds | idAnnonceur, libelleNomAnnonceur, adresseAnnonceur, villeAnnonceur, cpAnnonceur, paysAnnonceur, siretAnnonceur, motivAnnonceur and logoAnnonceur',
        ]);
        exit;
    
    elseif (empty(trim($data->idAnnonceur)) || empty(trim($data->libelleNomAnnonceur)) ||empty(trim($data->adresseAnnonceur)) || empty(trim($data->villeAnnonceur)) || empty(trim($data->cpAnnonceur)) || empty(trim($data->paysAnnonceur)) || empty(trim($data->logoAnnonceur)) || empty(trim($data->siretAnnonceur)) || empty(trim($data->motivAnnonceur))) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Field cannot be empty. Please fill all the fields.',
        ]);
        exit;
    
    endif;
    
    try 
    {
        $idAnnonceur = htmlspecialchars(trim($data->idAnnonceur));
        $libelleNomAnnonceur = htmlspecialchars(trim($data->libelleNomAnnonceur));
        $adresseAnnonceur = htmlspecialchars(trim($data->adresseAnnonceur));
        $villeAnnonceur = htmlspecialchars(trim($data->villeAnnonceur));
        $cpAnnonceur = htmlspecialchars(trim($data->cpAnnonceur));
        $paysAnnonceur = htmlspecialchars(trim($data->paysAnnonceur));
        $logoAnnonceur = htmlspecialchars(trim($data->logoAnnonceur));
        $siretAnnonceur = htmlspecialchars(trim($data->siretAnnonceur));
        $motivAnnonceur = htmlspecialchars(trim($data->motivAnnonceur));

        $query = "INSERT INTO `annonceurs`(idAnnonceur,libelleNomAnnonceur,adresseAnnonceur,villeAnnonceur,cpAnnonceur,paysAnnonceur,logoAnnonceur,siretAnnonceur,motivAnnonceur,idEtat)
        VALUES(:idAnnonceur,:libelleNomAnnonceur,:adresseAnnonceur,:villeAnnonceur,:cpAnnonceur,:paysAnnonceur,:logoAnnonceur,:siretAnnonceur,:motivAnnonceur,1)";
    
        $stmt = $cnx->prepare($query);
        
        $stmt->bindValue(':idAnnonceur', $idAnnonceur, PDO::PARAM_INT);
        $stmt->bindValue(':libelleNomAnnonceur', $libelleNomAnnonceur, PDO::PARAM_STR);
        $stmt->bindValue(':adresseAnnonceur', $adresseAnnonceur, PDO::PARAM_STR);
        $stmt->bindValue(':villeAnnonceur', $villeAnnonceur, PDO::PARAM_STR);
        $stmt->bindValue(':cpAnnonceur', $cpAnnonceur, PDO::PARAM_STR);
        $stmt->bindValue(':paysAnnonceur', $paysAnnonceur, PDO::PARAM_STR);
        $stmt->bindValue(':logoAnnonceur', $logoAnnonceur, PDO::PARAM_STR);
        $stmt->bindValue(':siretAnnonceur', $siretAnnonceur, PDO::PARAM_STR);
        $stmt->bindValue(':motivAnnonceur', $motivAnnonceur, PDO::PARAM_STR);

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