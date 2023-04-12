<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header('Access-Control-Allow-Credentials', true);
header('Access-Control-Allow-Methods: GET');
header("Content-Type: application/json; charset=UTF-8");


require_once 'db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();

$idImage = $_GET['idImage'];

$sql = "SELECT libelleImage, idProduitImage from image WHERE idImage = '$idImage'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$image = $stmt->fetch(PDO::FETCH_ASSOC);
$libelleImageSupp = $image['libelleImage'];
$idProduitImage = $image['idProduitImage'];

$sql = "SELECT libelleImage from image WHERE idProduitImage = '$idProduitImage'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

$libelleImage = explode('-', $libelleImageSupp);
$libelleImage = explode('.', $libelleImage[1]);
$numImg = strval($libelleImage[0]);

try {

    if(count($images) > 1) {
        if($numImg == "0") {

            $sql = "DELETE FROM image WHERE idImage = '$idImage'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
    
            $sql = "DELETE FROM annonce WHERE idAnnonceProduit = '$idProduitImage'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            
    
            if(isset($images[1])) {
                $image1 = explode('-', $images[1]['libelleImage']);
                $nom = $image1[0];
                $fileToRemove = $images[1]['libelleImage'];
                $ext = explode('.', $image1[1]);
                unlink("photos/produits/".$images[0]['libelleImage']);
                rename("photos/produits/".$images[1]['libelleImage'], "./photos/produits/".$nom."-0.".$ext[1]);
                $renamedFiles = $nom."-0.".$ext[1];
                $sql = "UPDATE image SET libelleImage = '$renamedFiles' WHERE libelleImage ='$fileToRemove'";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                echo json_encode([
                    'success' => 200,
                    'message' => "Image supprimée avec succès.",
                ]);
                exit;
            }
            if(isset($images[2])) {
                $image2 = explode('-', $images[2]['libelleImage']);
                $nom = $image2[0];
                $fileToRemove = $images[2]['libelleImage'];
                $ext = explode('.', $image2[1]);
                unlink("photos/produits/".$images[1]['libelleImage']);
                rename("photos/produits/".$images[2]['libelleImage'], "./photos/produits/".$nom."-1.".$ext[1]);
                $renamedFiles = $nom."-1.".$ext[1];
                $sql = "UPDATE image SET libelleImage = '$renamedFiles' WHERE libelleImage ='$fileToRemove'";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                echo json_encode([
                    'success' => 200,
                    'message' => "Image supprimée avec succès.",
                ]);
                exit;
            }
    
        } else if($numImg == "1") {
    
            $sql = "DELETE FROM image WHERE idImage = '$idImage'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
    
            $sql = "DELETE FROM annonce WHERE idAnnonceProduit = '$idProduitImage'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            
    
            if(isset($images[2])) {
                $image2 = explode('-', $images[2]['libelleImage']);
                $nom = $image2[0];
                $ext = explode('.', $image2[1]);
                $fileToRemove = $images[2]['libelleImage'];
                unlink("photos/produits/".$images[1]['libelleImage']);
                rename("photos/produits/".$images[2]['libelleImage'], "./photos/produits/".$nom."-1.".$ext[1]);
                $renamedFiles = $nom."-1.".$ext[1];
                $sql = "UPDATE image SET libelleImage = '$renamedFiles' WHERE libelleImage ='$fileToRemove'";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            }
            echo json_encode([
                'success' => 200,
                'message' => "Image supprimée avec succès.",
            ]);
            exit;
        } else if($numImg == "2") {
            unlink("photos/produits/".$libelleImageSupp);
            $sql = "DELETE FROM image WHERE idImage = '$idImage'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
    
            $sql = "DELETE FROM annonce WHERE idAnnonceProduit = '$idProduitImage'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
    
            echo json_encode([
                'success' => 200,
                'message' => "Image supprimée avec succès.",
            ]);
            exit;
    
        }
    } else {
        echo json_encode([
            'success' => 500,
            'message' => "Vous devez garder au moins une image.",
        ]);
        exit;
    }
    
    
    
} catch (Exception $e) {
    echo json_encode([
        'success' => 500,
        'message' => "Erreur lors de la suppression de l'image."
    ]);
}


    


?>