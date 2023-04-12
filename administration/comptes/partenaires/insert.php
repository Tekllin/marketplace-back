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

    if (!isset($data->idPartenaire) || !isset($data->libelleNomPartenaire) || !isset($data->libelleNomPartenaire)) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Please enter compulsory fileds | idPartenaire, libelleNomPartenaire and logoPartenaire',
        ]);
        exit;
    
    elseif (empty(trim($data->idPartenaire)) || empty(trim($data->libelleNomPartenaire)) || empty(trim($data->logoPartenaire))) :
    
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
        $logoPartenaire = htmlspecialchars(trim($data->logoPartenaire));

        $query = "INSERT INTO `partenaires`(idPartenaire,libelleNomPartenaire,logoPartenaire)
        VALUES(:idPartenaire,:libelleNomPartenaire,:logoPartenaire)";
    
        $stmt = $cnx->prepare($query);
        
        $stmt->bindValue(':idPartenaire', $idPartenaire, PDO::PARAM_INT);
        $stmt->bindValue(':libelleNomPartenaire', $libelleNomPartenaire, PDO::PARAM_STR);
        $stmt->bindValue(':logoPartenaire', $logoPartenaire, PDO::PARAM_STR);

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