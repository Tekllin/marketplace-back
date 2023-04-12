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
    
    require '../db_connect.php';
    $db = new Operations();
    $cnx = $db->dbConnection();
    
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->nomUser) || !isset($data->prenomUser) || !isset($data->emailUser) || !isset($data->passwordUser) || !isset($data->telUser) || !isset($data->idDroitUser)) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Please enter compulsory fileds | nomUser, prenomUser, emailUser, passwordUser, telUser and idDroitUser',
        ]);
        exit;
    
    elseif (empty(trim($data->nomUser)) || empty(trim($data->prenomUser)) || empty(trim($data->emailUser)) || empty(trim($data->passwordUser)) || empty(trim($data->telUser)) || empty(trim($data->idDroitUser))) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Field cannot be empty. Please fill all the fields.',
        ]);
        exit;
    
    endif;
    
    try 
    {
        $nomUser = htmlspecialchars(trim($data->nomUser));
        $prenomUser = htmlspecialchars(trim($data->prenomUser));
        $emailUser = htmlspecialchars(trim($data->emailUser));
        $passwordUser = htmlspecialchars(trim($data->passwordUser));
        $telUser = htmlspecialchars(trim($data->telUser));
        $tokenInsc = 0;
        $idDroitUser = htmlspecialchars(trim($data->idDroitUser));

        $query = "INSERT INTO `user`(nomUser,prenomUser,emailUser,passwordUser,telUser,tokenInsc,idDroitUser,idCoefPointsCredit,soldePointsCredit)
        VALUES(:nomUser,:prenomUser,:emailUser,:passwordUser,:telUser,:tokenInsc,:idDroitUser,1,0)";
    
        $stmt = $cnx->prepare($query);
        
        $stmt->bindValue(':nomUser', $nomUser, PDO::PARAM_STR);
        $stmt->bindValue(':prenomUser', $prenomUser, PDO::PARAM_STR);
        $stmt->bindValue(':emailUser', $emailUser, PDO::PARAM_STR);
        $stmt->bindValue(':passwordUser', $passwordUser, PDO::PARAM_STR);
        $stmt->bindValue(':telUser', $telUser, PDO::PARAM_STR);
        $stmt->bindValue(':tokenInsc', $tokenInsc, PDO::PARAM_STR);
        $stmt->bindValue(':idDroitUser', $idDroitUser, PDO::PARAM_INT);

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