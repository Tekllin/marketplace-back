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

    if (!isset($data->libelleCategorie) || !isset($data->couleurCategorie)) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Please enter compulsory fileds | libelleCategorie and couleurCategorie',
        ]);
        exit;
    
    elseif (empty(trim($data->libelleCategorie)) || empty(trim($data->couleurCategorie))) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Field cannot be empty. Please fill all the fields.',
        ]);
        exit;
    
    endif;

    try 
    {
        $libelleCategorie = htmlspecialchars(trim($data->libelleCategorie));
        $couleurCategorie = htmlspecialchars(trim($data->couleurCategorie));
        
        $query = "INSERT INTO `cattutoactu`(libelleCategorie,couleurCategorie)
        VALUES(:libelleCategorie,:couleurCategorie)";
    
        $stmt = $cnx->prepare($query);
        
        $stmt->bindValue(':libelleCategorie', $libelleCategorie, PDO::PARAM_STR);
        $stmt->bindValue(':couleurCategorie', $couleurCategorie, PDO::PARAM_STR);
        
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