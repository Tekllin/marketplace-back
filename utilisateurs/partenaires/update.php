<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: PUT");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == "OPTIONS") 
    {
        die();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') :
        http_response_code(405);
        echo json_encode([
            'success' => 0,
            'message' => 'Bad Request detected! Only PUT method is allowed',
        ]);
        exit;
    endif;

    require '../../db_connect.php';
    $db = new Operations();
    $cnx = $db->dbConnection();

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->idPartenaire)) 
    {
        echo json_encode(['success' => 0, 'message' => 'Please enter correct partenaire id.']);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT idPartenaire, nomSociete, adresseSociete, villeSociete, cpSociete, pays, logo, siret, motivPartenaire, idEtatPartenaire FROM partenaires WHERE idPartenaire = :idPartenaire";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':idPartenaire', $data->idPartenaire, PDO::PARAM_INT);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
            $idPartenaire = $data->idPartenaire;
            $idPartenaireModif = isset($data->idPartenaireModif) && !empty($data->idPartenaireModif) ? $data->idPartenaireModif : $row['idPartenaire'];
            $libelleNomPartenaire = isset($data->libelleNomPartenaire) && !empty($data->libelleNomPartenaire) ? $data->libelleNomPartenaire : $row['nomSociete'];
            $adressePartenaire = isset($data->adressePartenaire) && !empty($data->adressePartenaire) ? $data->adressePartenaire : $row['adresseSociete'];
            $villePartenaire = isset($data->villePartenaire) && !empty($data->villePartenaire) ? $data->villePartenaire : $row['villeSociete'];
            $cpPartenaire = isset($data->cpPartenaire) && !empty($data->cpPartenaire) ? $data->cpPartenaire : $row['cpSociete'];
            $paysPartenaire = isset($data->paysPartenaire) && !empty($data->paysPartenaire) ? $data->paysPartenaire : $row['pays'];
            $logoPartenaire = isset($data->logoPartenaire) && !empty($data->logoPartenaire) ? $data->logoPartenaire : $row['logo'];
            $siret = isset($data->siret) && !empty($data->siret) ? $data->siret : $row['siret'];
            $motivPartenaire = isset($data->motivPartenaire) && !empty($data->motivPartenaire) ? $data->motivPartenaire : $row['motivPartenaire'];
            $idEtatPartenaire = isset($data->idEtatPartenaire) && !empty($data->idEtatPartenaire) ? $data->idEtatPartenaire : $row['idEtatPartenaire'];

            $update_query = "UPDATE `partenaires` 
            SET idPartenaire = :idPartenaireModif, 
            nomSociete = :libelleNomPartenaire,
            adresseSociete = :adressePartenaire,
            villeSociete = :villePartenaire,
            cpSociete = :cpPartenaire,
            pays = :paysPartenaire,
            logo = :logoPartenaire,
            siret = :siret,
            motivPartenaire = :motivPartenaire,
            idEtatPartenaire = :idEtatPartenaire
            WHERE idPartenaire = :idPartenaire";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':idPartenaireModif', htmlspecialchars(strip_tags($idPartenaireModif)), PDO::PARAM_INT);
            $update_stmt->bindValue(':libelleNomPartenaire', htmlspecialchars(strip_tags($libelleNomPartenaire)), PDO::PARAM_STR);
            $update_stmt->bindValue(':adressePartenaire', htmlspecialchars(strip_tags($adressePartenaire)), PDO::PARAM_STR);
            $update_stmt->bindValue(':villePartenaire', htmlspecialchars(strip_tags($villePartenaire)), PDO::PARAM_STR);
            $update_stmt->bindValue(':cpPartenaire', htmlspecialchars(strip_tags($cpPartenaire)), PDO::PARAM_STR);
            $update_stmt->bindValue(':paysPartenaire', htmlspecialchars(strip_tags($paysPartenaire)), PDO::PARAM_STR);
            $update_stmt->bindValue(':logoPartenaire', htmlspecialchars(strip_tags($logoPartenaire)), PDO::PARAM_STR);
            $update_stmt->bindValue(':siret', htmlspecialchars(strip_tags($siret)), PDO::PARAM_STR);
            $update_stmt->bindValue(':motivPartenaire', htmlspecialchars(strip_tags($motivPartenaire)), PDO::PARAM_STR);
            $update_stmt->bindValue(':idEtatPartenaire', htmlspecialchars(strip_tags($idEtatPartenaire)), PDO::PARAM_INT);

            $update_stmt->bindValue(':idPartenaire', $data->idPartenaire, PDO::PARAM_INT);


            if ($update_stmt->execute()) 
            {
                echo json_encode([
                    'success' => 1,
                    'message' => 'Record udated successfully'
                ]);
                exit;
            }

            echo json_encode([
                'success' => 0,
                'message' => 'Did not udpate. Something went  wrong.'
            ]);
            exit;

        else :
            echo json_encode(['success' => 0, 'message' => 'Invalid ID. No record found by the ID.']);
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