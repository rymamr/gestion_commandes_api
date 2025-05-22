<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['idProforma']) && !empty($data['codeClient']) && !empty($data['dateProforma'])) {
    $idProforma = $data['idProforma'];
    $codeClient = $data['codeClient'];
    $dateProforma = $data['dateProforma'];

    $sql = "UPDATE proforma SET codeClient = ?, dateProforma = ? WHERE idProforma = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $codeClient, $dateProforma, $idProforma);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Proforma modifiée avec succès"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur lors de la modification"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Données manquantes"]);
}
?>
