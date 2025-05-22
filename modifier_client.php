<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'db.php';

// Récupérer les données JSON envoyées par le frontend
$input = json_decode(file_get_contents("php://input"), true);

$codeClient = isset($input['codeClient']) ? $input['codeClient'] : null;
$nomClient  = isset($input['nomClient']) ? $input['nomClient'] : null;

if ($codeClient && $nomClient) {
    $sql = "UPDATE client SET nomClient = '$nomClient' WHERE codeClient = '$codeClient'";
    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => "Client modifié avec succès!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur SQL : " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Données manquantes"]);
}
?>
