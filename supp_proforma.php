<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['idProforma'])) {
    $idProforma = $data['idProforma'];

    $sql = "DELETE FROM proforma WHERE idProforma = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idProforma);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Proforma supprimée"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur lors de la suppression"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ID de la proforma requis"]);
}
?>
