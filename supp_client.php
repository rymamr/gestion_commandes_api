<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
include 'db.php';

// Active le mode strict pour voir les erreurs SQL
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Récupération des données envoyées
$input = json_decode(file_get_contents("php://input"), true);
$codeClient = $input['codeClient'] ?? ($_POST['codeClient'] ?? null);
if (!$codeClient) {
    echo json_encode(["success" => false, "message" => "Données manquantes"]);
    exit;
}

// Préparer la requête SQL
$stmt = $conn->prepare("DELETE FROM client WHERE codeClient = ?");
if (!$stmt) {
    error_log("Erreur de préparation SQL: " . $conn->error);
    echo json_encode(["success" => false, "message" => "Erreur SQL: " . $conn->error]);
    exit;
}

$stmt->bind_param("s", $codeClient);

if ($stmt->execute()) {
    $affected = $stmt->affected_rows;
    echo json_encode([
        "success" => true,
        "message" => $affected > 0 ? "Client supprimé avec succès !" : "Aucun client trouvé.",
        "affected" => $affected
    ]);
} else {
    error_log("Erreur lors de l'exécution de la requête: " . $stmt->error);
    echo json_encode(["success" => false, "message" => "Erreur d'exécution : " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
   