<?php
    error_reporting(0);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') :
        http_response_code(405);
        echo json_encode([
            'success' => 0,
            'message' => 'Bad Reqeust Detected! Only get method is allowed',
        ]);
        exit;
    endif;
   
    require 'db_connect.php';
    $db = new Operations();
    $cnx = $db->dbConnection();

    $codePays = null;

    if (isset($_GET['codePays'])) 
    {
        $codePays = filter_var($_GET['codePays'], FILTER_VALIDATE_INT, [
            'options' => [
                'default' => 'all_pays',
                'min_range' => 1
            ]
        ]);
    }

    try 
    {
        $sql = is_numeric($codePays) ? "SELECT codePays, libellePays FROM pays WHERE codePays = $codePays" : "SELECT codePays, libellePays FROM pays";
        $stmt = $cnx->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'success' => 1,
                'data' => $data,
            ]);
            exit;

        }else{
            echo json_encode([
                'success' => 0,
                'message' => 'No Record Found!',
            ]);
        };
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