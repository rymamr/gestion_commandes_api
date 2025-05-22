<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'db.php';

// Débogage : Sauvegarde les données reçues dans un fichier log
$rawData = file_get_contents("php://input");
file_put_contents("log_modification.txt", "Données reçues : " . $rawData . "\n", FILE_APPEND);

$input = json_decode($rawData, true);

// Récupération et sécurisation des données
$codeProduit   = isset($input['codeProduit']) ? $conn->real_escape_string($input['codeProduit']) : null;
$designation   = isset($input['designation']) ? $conn->real_escape_string($input['designation']) : null;
$suite         = isset($input['suite']) ? $conn->real_escape_string($input['suite']) : null;
$prixAchatHT   = isset($input['prixAchatHT']) ? floatval($input['prixAchatHT']) : null;
$totalHT       = isset($input['totalHT']) ? floatval($input['totalHT']) : null; // Correction ici
$TVA           = isset($input['TVA']) ? floatval($input['TVA']) : null;

// Vérification des données reçues
if ($codeProduit !== null && $designation !== null && $suite !== null && $prixAchatHT !== null && $totalHT !== null && $TVA !== null) {
    $sql = "UPDATE produit SET 
                designation = '$designation', 
                suite = '$suite', 
                prixAchatHT = $prixAchatHT, 
                totalHT = $totalHT, 
                TVA = $TVA 
            WHERE codeProduit = '$codeProduit'";

    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => "Produit modifié avec succès!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur SQL : " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Données manquantes"]);
}

$conn->close();
?>
