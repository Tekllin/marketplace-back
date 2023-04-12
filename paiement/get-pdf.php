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
        $sql = "SELECT idAnnonceFacture, refFacture, dateAnnoFacture, libelleOffre, prixOffre, libelleNomAnnonceur, libelleAdresse, cpAdresse, villeAdresse, paysAdresse FROM annonceFacture 
        INNER JOIN concerner ON idAnnonceFactureConcerne = idAnnonceFacture 
        INNER JOIN typeOffre ON idTypeOffreConcerne = idTypeOffre
        INNER JOIN annonceurs ON idAnnonceurAnnoFacture = idAnnonceur
        INNER JOIN user ON idAnnonceur = idUser
        INNER JOIN facturations ON idAdresseAnnoFacture = idAdresse
        WHERE refFacture = '$_GET[hash]'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
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
        $this->Cell(30,10,$result['dateAnnoFacture'],0,0,'C');

        $this->Cell(120,10,'Facture',0,0,'C');

        $this->Cell(30,10,utf8_decode("N°").$result['refFacture']."",0,0,'C');
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
            $result['idAnnonceFacture']
        );
        $this->SetFont('Arial','B',10);
        $this->Cell(17,7,'Altameos',0,0,'L');
        $this->SetFont('Arial','',10);
        $this->Ln(5);
        $this->Cell(39,7,utf8_decode('9 Bis rue du Puits Carré'),0,0,'L');
        $this->SetFont('Arial','B',10);
        $this->Cell(143,7,utf8_decode($result['libelleNomAnnonceur']),0,0,'R');
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
        $this->Cell(40,7,'Prix',1);
        $this->Cell(30,7,'',1);
        $this->Cell(40,7,'Prix TTC',1);
        $this->Ln(10);
        $result = dbConnect();
        // Données 
        $this->SetFont('Arial','',10);
        $this->Cell(40,7,utf8_decode($result['libelleOffre']),0);
        $this->Cell(30,7,'1',0);
        $this->Cell(40,7,$result['prixOffre'],0);
        $this->Cell(30,7,'',0);
        $this->SetFont('Arial','b',10);
        $this->Cell(40,7,round((doubleval($result['prixOffre'])),2),0);
        $this->SetFont('Arial','',10);
        $this->Ln(100);        
        // Récapitulatif du prix total
        $this->Cell(130,10,'',0,0,'R');
        $this->Ln(10);
        $this->Cell(130,10,'',0,0,'R');
        $this->SetFont('Arial','b',10);
        $this->Cell(30,10,'Prix TTC',1,0,'R');
        $this->Cell(30,10,round((doubleval($result['prixOffre'])),2).utf8_decode(' EUR'),1,0,'R');
        $this->SetFont('Arial','',10);
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
    $pdf->Output('F', '../factures/'.$nomfacture.'.pdf');
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    
    
       
 
   
    
    
?>