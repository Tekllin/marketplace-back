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

    if (!isset($data->idAnnonceurProduit) || !isset($data->libelleProduit) || !isset($data->qtStock) || !isset($data->descriptionProduit) || !isset($data->idCategorieProduit) || !isset($data->prixUnitHT)) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Please enter compulsory fileds | idAnnonceurProduit, libelleProduit, qtStock, descriptionProduit, idCategorieProduit and prixUnitHT',
        ]);
        exit;
    
    elseif (empty(trim($data->idAnnonceurProduit)) || empty(trim($data->libelleProduit)) || empty(trim($data->qtStock)) || empty(trim($data->descriptionProduit)) || empty(trim($data->idCategorieProduit)) || empty(trim($data->prixUnitHT))) :
    
        echo json_encode([
            'success' => 0,
            'message' => 'Field cannot be empty. Please fill all the fields.',
        ]);
        exit;
    
    endif;
    
    try 
    {
        $idAnnonceurProduit = htmlspecialchars(trim($data->idAnnonceurProduit));
        $libelleProduit = htmlspecialchars(trim($data->libelleProduit));
        $qtStock = htmlspecialchars(trim($data->qtStock));
        $descriptionProduit = htmlspecialchars(trim($data->descriptionProduit));
        $idCategorieProduit = htmlspecialchars(trim($data->idCategorieProduit));
        $prixUnitHT = htmlspecialchars(trim($data->prixUnitHT));

        $query = "INSERT INTO `produits`(idAnnonceurProduit,libelleProduit,qtStock,descriptionProduit,idCategorieProduit,prixUnitHT)
        VALUES(:idAnnonceurProduit,:libelleProduit,:qtStock,:descriptionProduit,:idCategorieProduit,:prixUnitHT)";
    
        $stmt = $cnx->prepare($query);
        
        $stmt->bindValue(':idAnnonceurProduit', $idAnnonceurProduit, PDO::PARAM_INT);
        $stmt->bindValue(':libelleProduit', $libelleProduit, PDO::PARAM_STR);
        $stmt->bindValue(':qtStock', $qtStock, PDO::PARAM_INT);
        $stmt->bindValue(':descriptionProduit', $descriptionProduit, PDO::PARAM_STR);
        $stmt->bindValue(':idCategorieProduit', $idCategorieProduit, PDO::PARAM_INT);
        $stmt->bindValue(':prixUnitHT', $prixUnitHT, PDO::PARAM_STR);

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