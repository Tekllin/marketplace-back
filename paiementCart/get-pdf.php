<?php
    error_reporting(0);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header('Access-Control-Allow-Credentials', true);
    header('Access-Control-Allow-Methods: GET');
    header("Content-Type: application/json; charset=UTF-8");
    require('../vendor/fpdf/fpdf.php');
    

    require_once '../vendor/autoload.php';
    require_once('../db_connect.php');

    
   
    try {
        //Recuperer les informations depuis une base données
    function dbConnect() {
        $database = new Operations();
        $conn = $database->dbConnection();
        $sql = "SELECT numFacture, dateFacture, nomUser, prenomUser, libelleAdresse, cpAdresse, villeAdresse, paysAdresse/*, libelleProduit, qtProduitComm, prixUnitHT, pourcTva*/
                FROM commandefacture M
                INNER JOIN user U ON M.idUserFacture = U.idUser
                INNER JOIN facturations F ON M.idFacturationFacture = F.idAdresse
                /*INNER JOIN commander C ON C.numFactComm = M.numFacture
                INNER JOIN produits P ON P.idProduit = C.idProduitComm
                INNER JOIN tva T ON T.idTVA = P.idTvaProduit
                INNER JOIN user U ON C.idUserComm = U.idUser
                INNER JOIN facturations F ON U.idUser = F.idUserAdresse*/
                WHERE numFacture = '$_GET[hash]'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    function commandeProduits() {
        $database = new Operations();
        $conn = $database->dbConnection();
        $sql = "SELECT numFacture, libelleProduit, qtProduitComm, prixUnitHT, pourcTva
                FROM commandefacture M
                INNER JOIN commander C ON C.numFactComm = M.numFacture
                INNER JOIN produits P ON P.idProduit = C.idProduitComm
                INNER JOIN tva T ON T.idTVA = P.idTvaProduit
                WHERE numFacture = '$_GET[hash]'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    class PDF extends FPDF
    {
    
        // En-tête
        function Header()
        {
            $result = dbConnect();
            
            // Police Arial gras 15
            $this->SetFont('Arial','B',15);
            // Décalage à droite
            // Titre
            $this->Cell(30,10,$result['dateFacture'],0,0,'C');

            $this->Cell(120,10,'Facture',0,0,'C');

            $this->Cell(30,10,utf8_decode("N°").$result['numFacture']."",0,0,'C');
            // Saut de ligne
            $this->Ln(20);
            
        }
        
        // Pied de page
        function Footer()
        {
            // Positionnement à 1,5 cm du bas
            $this->SetY(-15);
            // Police Arial italique 8
            $this->SetFont('Arial','I',8);
            // Numéro de page
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
            // Numero siret, APE, TVA
            $this->Ln(5);
            $this->SetFont('Arial','',8);
            $this->Cell(0,10,utf8_decode('SIRET | Code APE | Numero TVA'),0,0,'C');

        }
        function adresse() {
            
            $result = dbConnect();
            require_once './secrets.php';
            \Stripe\Stripe::setApiKey($stripeSecretKey);
            $stripe = new \Stripe\StripeClient($stripeSecretKey);
            $paymentIntent = $stripe->paymentIntents->retrieve(
                $_GET['payment']
            );
            
            $this->SetFont('Arial','B',10);
            $this->Cell(17,7,'Altameos',0,0,'L');
            $this->SetFont('Arial','',10);
            $this->Ln(5);
            $this->Cell(39,7,utf8_decode('9 Bis rue du Puits Carré'),0,0,'L');
            $this->SetFont('Arial','B',10);
            $this->Cell(143,7,utf8_decode($result['nomUser']),0,0,'R');
            $this->SetFont('Arial','',10);
            $this->Ln(5);
            $this->Cell(40,7,'27000 Evreux',0,0,'L');
            $this->Cell(142,7,utf8_decode($paymentIntent['metadata']['adresse']),0,0,'R');
            $this->Ln(5);
            $this->Cell(40,7,'France',0);
            $this->Cell(142,7,utf8_decode($paymentIntent['metadata']['cp'] .' '. $paymentIntent['metadata']['ville']),0,0,'R');
            $this->Ln(5);
            $this->Cell(182,7,utf8_decode($paymentIntent['metadata']['pays']),0,0,'R');
            $this->Ln(30);
            $this->Cell(40,7,utf8_decode("Vous trouverez ci dessous le récapitulatif de votre commande:"),0,0,'L');
            $this->Ln(10);
        }

        function BasicTable()
        {
            // 
            $this->SetFont('Arial','b',10);
            $this->SetFillColor(255,0,0);
            $this->SetDrawColor(189,189,189);
            $this->Cell(40,7,'Produit',1);
            $this->Cell(30,7,'Quantite',1);
            $this->Cell(40,7,'Prix Unitaire HT',1);
            $this->Cell(30,7,'TVA',1);
            $this->Ln(10);
            $results = commandeProduits();

            foreach($results as $result) {
                // Données 
                $this->SetFont('Arial','',10);
                $this->Cell(40,7,utf8_decode($result['libelleProduit']),0);
                $this->Cell(30,7,$result['qtProduitComm'],0);
                $this->Cell(40,7,$result['prixUnitHT'].utf8_decode(' EUR'),0);
                $this->Cell(30,7,$result['pourcTva'].utf8_decode(' %'),0);
                $this->SetFont('Arial','',10);
                $this->Ln(7.5);
            }

            $total = $_GET['total'] / 100;

            // Récapitulatif des frais de port
            $this->Ln(20);
            $this->Cell(130,10,'',0,0,'R');
            $this->Cell(30,10,'Frais de port',1,0,'R');
            $this->Cell(30,10,$_GET['fraisDePort'].utf8_decode(' EUR'),1,0,'R');
            $this->Ln(20);

            // Récapitulatif du prix total
            $this->Cell(130,10,'',0,0,'R');
            $this->Cell(30,10,'Prix Total TTC',1,0,'R');
            $this->Cell(30,10,$total.utf8_decode(' EUR'),1,0,'R');
            $this->Ln(20);
        }
    }

    
    
    // Instanciation de la classe dérivée
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->adresse();
    $pdf->BasicTable();
    $pdf->Output();
    // Sauvegarder le fichier pdf en local
    $nomfacture = $_GET['hash'];
    $pdf->Output('F', '../facturesCartPayment/'.$nomfacture.'.pdf');
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    
    
       
 
   
    
    
?>