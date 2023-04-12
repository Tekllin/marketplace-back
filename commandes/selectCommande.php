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
    
    $produit_id = null;
    $facture_id = null;
    
    if (isset($_GET['idProduit'])) 
    {
        $produit_id = filter_var($_GET['idProduit'], FILTER_VALIDATE_INT, [
            'options' => [
                'default' => 'all_annonces',
                'min_range' => 1
            ]
        ]);
    }

    if (isset($_GET['idFacture'])) 
    {
        $facture_id = $_GET['idFacture'];
    }

    try 
    {
        $sql = "SELECT * FROM commander INNER JOIN produits ON produits.idProduit = commander.idProduitComm";
        $stmt = $cnx->prepare($sql);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) :
    
            $data = null;
            if (is_numeric($produit_id) && is_string($facture_id)) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
    
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