<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'db.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (
    isset($data->idCommande, $data->codeClient, $data->dateCommande, $data->totalHT, $data->totalTTC, $data->TVA)
) {
    $idCommande = $data->idCommande;
    $codeClient = $data->codeClient;
    $dateCommande = $data->dateCommande;
    $totalHT = $data->totalHT;
    $totalTTC = $data->totalTTC;
    $TVA = $data->TVA;

    $query = "UPDATE commande SET codeClient=?, dateCommande=?, totalHT=?, totalTTC=?, TVA=? 
              WHERE idCommande=?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdddi", $codeClient, $dateCommande, $totalHT, $totalTTC, $TVA, $idCommande);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Commande mise à jour"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur lors de la modification"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Données incomplètes"]);
}
?>
