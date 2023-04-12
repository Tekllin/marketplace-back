<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == "OPTIONS") 
    {
        die();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') :
        http_response_code(405);
        echo json_encode([
            'success' => 0,
            'message' => 'Bad Reqeust detected. HTTP method should be DELETE',
        ]);
        exit;
    endif;

    require '../db_connect.php';
    $db = new Operations();
    $cnx = $db->dbConnection();

    $data = json_decode(file_get_contents("php://input"));

    $idProduit =  $_GET['id'];

    if (!isset($idProduit)) {
        echo json_encode(['success' => 0, 'message' => 'Please provide the produit ID.']);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT idProduit, idAnnonceurProduit, libelleProduit, qtStock, descriptionProduit, idCategorieProduit, prixUnitHT FROM produits WHERE idProduit = :idProduit";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':idProduit', $idProduit, PDO::PARAM_INT);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :

            $delete_post = "DELETE FROM `produits` WHERE idProduit=:idProduit";
            $delete_post_stmt = $cnx->prepare($delete_post);
            $delete_post_stmt->bindValue(':idProduit', $idProduit,PDO::PARAM_INT);

            if ($delete_post_stmt->execute()) 
            {
                echo json_encode([
                    'success' => 1,
                    'message' => 'Record Deleted successfully'
                ]);
                exit;
            }

            echo json_encode([
                'success' => 0,
                'message' => 'Could not delete. Something went wrong.'
            ]);
            exit;

        else :
            echo json_encode(['success' => 0, 'message' => 'Invalid ID. No produit found by the ID.']);
            exit;
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