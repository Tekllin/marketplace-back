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

    if (!isset($data->titrePage) || !isset($data->contenuPage) || !isset($data->resumePage) || !isset($data->datePublication) || !isset($data->imgPreview) || !isset($data->boolPage)) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Please enter compulsory fileds | titrePage, contenuPage, resumePage, datePublication, imgPreview, boolPage and idCatPage',
        ]);
        exit;
    
    elseif (empty(trim($data->titrePage)) || empty(trim($data->contenuPage)) || empty(trim($data->resumePage)) || empty(trim($data->datePublication)) || empty(trim($data->imgPreview)) || (empty(trim($data->boolPage)) && $data->boolPage != 0)) :
        echo json_encode([
            'success' => 0,
            'message' => 'Field cannot be empty. Please fill all the fields.',
        ]);
        exit;
    
    endif;

    try 
    {
        $titrePage = htmlspecialchars(trim($data->titrePage));
        $contenuPage = htmlentities(htmlspecialchars(trim($data->contenuPage)));
        $videoPage = htmlspecialchars(htmlspecialchars(trim($data->videoPage)));
        $resumePage = htmlspecialchars(trim($data->resumePage));
        $datePublication = htmlspecialchars(trim($data->datePublication));
        $imgPreview = htmlspecialchars(trim($data->imgPreview));
        $boolPage = htmlspecialchars(trim($data->boolPage));

        $query = "INSERT INTO `pages`(
            titrePage,
            contenuPage,
            videoPage,
            resumePage,
            datePublication,
            imgPreview,
            boolPage)
        VALUES(
            :titrePage,
            :contenuPage,
            :videoPage, 
            :resumePage,
            :datePublication,
            :imgPreview,
            :boolPage)";
    
        $stmt = $cnx->prepare($query);
        
        $stmt->bindValue(':titrePage', $titrePage, PDO::PARAM_STR);
        $stmt->bindValue(':contenuPage', $contenuPage, PDO::PARAM_STR);
        $stmt->bindValue(':videoPage', $videoPage, PDO::PARAM_STR);
        $stmt->bindValue(':resumePage', $resumePage, PDO::PARAM_STR);
        $stmt->bindValue(':datePublication', $datePublication, PDO::PARAM_STR);
        $stmt->bindValue(':imgPreview', $imgPreview, PDO::PARAM_STR);
        $stmt->bindValue(':boolPage', $boolPage, PDO::PARAM_INT);

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