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

    if (!isset($data->idUserAdresse) || !isset($data->libelleAdresse) || !isset($data->cpAdresse) || !isset($data->villeAdresse) || !isset($data->paysAdresse) || !isset($data->etiquetteAdresse)) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Please enter compulsory fileds | idUserAdresse, libelleAdresse, cpAdresse, villeAdresse, paysAdresse and etiquetteAdresse',
        ]);
        exit;
    
    elseif (empty(trim($data->idUserAdresse)) || empty(trim($data->libelleAdresse)) || empty(trim($data->cpAdresse)) || empty(trim($data->villeAdresse)) || empty(trim($data->paysAdresse)) || empty(trim($data->etiquetteAdresse))) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Field cannot be empty. Please fill all the fields.',
        ]);
        exit;
    
    endif;
    
    try 
    {
        $idUserAdresse = htmlspecialchars(trim($data->idUserAdresse));
        $libelleAdresse = htmlspecialchars(trim($data->libelleAdresse));
        $cpAdresse = htmlspecialchars(trim($data->cpAdresse));
        $villeAdresse = htmlspecialchars(trim($data->villeAdresse));
        $paysAdresse = htmlspecialchars(trim($data->paysAdresse));
        $etiquetteAdresse = htmlspecialchars(trim($data->etiquetteAdresse));

        $query = "INSERT INTO `livraisons`(idUserAdresse,libelleAdresse,cpAdresse,villeAdresse,paysAdresse,etiquetteAdresse)
        VALUES(:idUserAdresse,:libelleAdresse,:cpAdresse,:villeAdresse,:paysAdresse,:etiquetteAdresse)";
    
        $stmt = $cnx->prepare($query);
        
        $stmt->bindValue(':idUserAdresse', $idUserAdresse, PDO::PARAM_INT);
        $stmt->bindValue(':libelleAdresse', $libelleAdresse, PDO::PARAM_STR);
        $stmt->bindValue(':cpAdresse', $cpAdresse, PDO::PARAM_STR);
        $stmt->bindValue(':villeAdresse', $villeAdresse, PDO::PARAM_STR);
        $stmt->bindValue(':paysAdresse', $paysAdresse, PDO::PARAM_STR);
        $stmt->bindValue(':etiquetteAdresse', $etiquetteAdresse, PDO::PARAM_STR);

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