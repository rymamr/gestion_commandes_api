<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['codeProduit'])) {
    $codeProduit = $data['codeProduit'];

    $sql = "DELETE FROM Produit WHERE codeProduit='$codeProduit'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Produit supprimé avec succès"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Code produit manquant"]);
}

$conn->close();
?>
