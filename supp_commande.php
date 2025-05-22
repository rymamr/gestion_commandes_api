<?php
include 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (isset($data->idCommande)) {
    $idCommande = $data->idCommande;

    $query = "DELETE FROM commande WHERE idCommande = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idCommande);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Commande supprimée"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur lors de la suppression"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ID commande manquant"]);
}
?>
