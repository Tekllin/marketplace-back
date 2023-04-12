<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");
    error_reporting(E_ERROR);

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') :
        http_response_code(405);
        echo json_encode([
            'success' => 0,
            'message' => 'Bad Reqeust Detected! Only get method is allowed',
        ]);
        exit;
    endif;
    
    require '../db_connect.php';
    $db = new Operations();
    $cnx = $db->dbConnection();
    
    try 
    {
        $sql = "SELECT logoSite, logoEnteteSite, descriptionSite, emailSite, adresseSite, cpSite, villeSite, paysSite, numeroSite, copyright, qsnSite, facebookSite, instagramSite, linkdinSite FROM infosite";
        $stmt = $cnx->prepare($sql);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) :
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data[0]['qsnSite'] = html_entity_decode(htmlspecialchars_decode($data[0]['qsnSite']));

            echo json_encode([
                'success' => 1,
                'data' => $data,
            ]);
    
        else :
            echo json_encode([
                'success' => 0,
                'message' => 'No Record Found!',
            ]);
        endif;
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