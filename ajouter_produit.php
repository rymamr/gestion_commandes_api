<?php
include 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Débogage : Vérifier si on reçoit des données
$rawData = file_get_contents("php://input");
file_put_contents("log.txt", "Données reçues : " . $rawData . "\n", FILE_APPEND);
$data = json_decode($rawData, true);

if (isset($data['codeProduit'], $data['designation'], $data['suite'], $data['prixAchatHT'], $data['totalHT'], $data['TVA'])) {
    $codeProduit = $conn->real_escape_string($data['codeProduit']);
    $designation = $conn->real_escape_string($data['designation']);
    $suite = $conn->real_escape_string($data['suite']);
    $prixAchatHT = floatval($data['prixAchatHT']);
    $totalHT = floatval($data['totalHT']);
    $TVA = floatval($data['TVA']);

    $sql = "INSERT INTO produit (codeProduit, designation, suite, prixAchatHT, totalHT, TVA) 
            VALUES ('$codeProduit', '$designation', '$suite', '$prixAchatHT', '$totalHT', '$TVA')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Produit ajouté avec succès"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur SQL : " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Données manquantes"]);
}

$conn->close();
?>
