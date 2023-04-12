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

        $libelleProduit = $_GET['libelleProduit'];

        $sql = "SELECT idProduit, idAnnonceurProduit, libelleProduit, qtStock, descriptionProduit, prixUnitHT, idCategorieProduit, libelleNomAnnonceur, AVG(CASE WHEN Avis.valid = 1 THEN Avis.noteAvis ELSE NULL END) as averageNote, MAX(Image.libelleImage) as image FROM produits 
        LEFT JOIN annonceurs ON idAnnonceur = idAnnonceurProduit 
        LEFT JOIN Avis ON Produits.idProduit = Avis.idProduitAvis 
        LEFT JOIN Image ON Produits.idProduit = Image.idProduitImage 
        WHERE libelleProduit LIKE '%$libelleProduit%' 
        GROUP BY idProduit;";
        $stmt = $cnx->prepare($sql);
        $stmt->execute();
        
    
        if ($stmt->rowCount() > 0) :
    
            $data = null;
            if (is_numeric($annonceur_id)) {
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