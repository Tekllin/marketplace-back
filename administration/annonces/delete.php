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

    $idAnnonceProduit = $_GET['idProduit'];
    $idAnnonceAnnoFacture = $_GET['idFacture'];
    $idEtat = $_GET['idEtat'];

    if (!isset($_GET['idProduit']) || !isset($_GET['idFacture']) || !isset($_GET['idEtat']))
    {
        echo json_encode([
            'success' => 0,
            'message' => 'Field cannot be empty. Please fill all the fields.',
        ]);
        exit;
    }
    else if (empty(trim($_GET['idProduit'])) || empty(trim($_GET['idFacture'])) || empty(trim($_GET['idEtat'])))
    {
        echo json_encode([
            'success' => 0,
            'message' => 'Please enter compulsory fileds | idAnnonceProduit, idAnnonceAnnoFacture et idEtat',
        ]);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT idAnnonceProduit, idAnnonceAnnoFacture, idEtat FROM annonce WHERE idAnnonceProduit = :idAnnonceProduit AND idAnnonceAnnoFacture = :idAnnonceAnnoFacture AND idEtat = :idEtat";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':idAnnonceProduit', $idAnnonceProduit, PDO::PARAM_INT);
        $fetch_stmt->bindValue(':idAnnonceAnnoFacture', $idAnnonceAnnoFacture, PDO::PARAM_INT);
        $fetch_stmt->bindValue(':idEtat', $idEtat, PDO::PARAM_INT);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :

            $delete_post = "DELETE FROM `annonce` WHERE idAnnonceProduit = :idAnnonceProduit AND idAnnonceAnnoFacture = :idAnnonceAnnoFacture AND idEtat = :idEtat";
            $delete_post_stmt = $cnx->prepare($delete_post);
            $delete_post_stmt->bindValue(':idAnnonceProduit', $idAnnonceProduit, PDO::PARAM_INT);
            $delete_post_stmt->bindValue(':idAnnonceAnnoFacture', $idAnnonceAnnoFacture, PDO::PARAM_INT);
            $delete_post_stmt->bindValue(':idEtat', $idEtat, PDO::PARAM_INT);

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
            echo json_encode(['success' => 0, 'message' => 'Invalid ID. No annonce found by the ID.']);
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