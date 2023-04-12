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

    if (!isset($data->titreActuTuto) || !isset($data->contenuActuTuto) || !isset($data->resumeActuTuto) || !isset($data->datePublication) || !isset($data->imgPreview) || !isset($data->boolActuTuto) || !isset($data->idCatActuTuto)) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Please enter compulsory fileds | titreActuTuto, contenuActuTuto, resumeActuTuto, datePublication, imgPreview, boolActuTuto and idCatActuTuto',
        ]);
        exit;
    
    elseif (empty(trim($data->titreActuTuto)) || empty(trim($data->contenuActuTuto)) || empty(trim($data->resumeActuTuto)) || empty(trim($data->datePublication)) || empty(trim($data->imgPreview)) || (empty(trim($data->boolActuTuto)) && $data->boolActuTuto != 0) || empty(trim($data->idCatActuTuto))) :
        echo json_encode([
            'success' => 0,
            'message' => 'Field cannot be empty. Please fill all the fields.',
        ]);
        exit;
    
    endif;

    try 
    {
        $titreActuTuto = htmlspecialchars(trim($data->titreActuTuto));
        $contenuActuTuto = htmlentities(htmlspecialchars(trim($data->contenuActuTuto)));
        $videoActuTuto = htmlspecialchars(htmlspecialchars(trim($data->videoActuTuto)));
        $resumeActuTuto = htmlspecialchars(trim($data->resumeActuTuto));
        $datePublication = htmlspecialchars(trim($data->datePublication));
        $imgPreview = htmlspecialchars(trim($data->imgPreview));
        $boolActuTuto = htmlspecialchars(trim($data->boolActuTuto));
        $idCatActuTuto = htmlspecialchars(trim($data->idCatActuTuto));

        $query = "INSERT INTO `tutoactu`(titreActuTuto,contenuActuTuto,videoActuTuto,resumeActuTuto,datePublication,imgPreview,boolActuTuto,idCatActuTuto)
        VALUES(:titreActuTuto,:contenuActuTuto,:videoActuTuto, :resumeActuTuto,:datePublication,:imgPreview,:boolActuTuto,:idCatActuTuto)";
    
        $stmt = $cnx->prepare($query);
        
        $stmt->bindValue(':titreActuTuto', $titreActuTuto, PDO::PARAM_STR);
        $stmt->bindValue(':contenuActuTuto', $contenuActuTuto, PDO::PARAM_STR);
        $stmt->bindValue(':videoActuTuto', $videoActuTuto, PDO::PARAM_STR);
        $stmt->bindValue(':resumeActuTuto', $resumeActuTuto, PDO::PARAM_STR);
        $stmt->bindValue(':datePublication', $datePublication, PDO::PARAM_STR);
        $stmt->bindValue(':imgPreview', $imgPreview, PDO::PARAM_STR);
        $stmt->bindValue(':boolActuTuto', $boolActuTuto, PDO::PARAM_INT);
        $stmt->bindValue(':idCatActuTuto', $idCatActuTuto, PDO::PARAM_INT);

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