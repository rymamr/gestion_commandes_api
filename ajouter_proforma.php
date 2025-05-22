<?php
include 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Lire les données JSON envoyées
$data = json_decode(file_get_contents("php://input"), true);

// Debug : écrire les données reçues dans un fichier log
file_put_contents("debug_proforma.txt", print_r($data, true), FILE_APPEND);

if (!isset($data["codeClient"], $data["dateProforma"], $data["produits"])) {
    echo json_encode(["success" => false, "message" => "Données incomplètes"]);
    exit;
}

$codeClient = $data["codeClient"];
$dateProforma = $data["dateProforma"];
$produits = $data["produits"];

$conn->begin_transaction(); // 🔹 Démarrer une transaction

// 1️⃣ Insérer la proforma
$query = "INSERT INTO proforma (codeClient, dateProforma, totalHT, totalTTC, TVA) VALUES (?, ?, 0, 0, 0)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Erreur préparation requête proforma"]);
    exit;
}

$stmt->bind_param("ss", $codeClient, $dateProforma);

if (!$stmt->execute()) {
    $conn->rollback(); // ❌ Annuler tout si erreur
    echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout de la proforma"]);
    exit;
}

$idProforma = $stmt->insert_id; // Récupérer l'ID de la proforma créée

// 2️⃣ Insérer les produits
$queryProduits = "INSERT INTO proforma_produit (idProforma, codeProduit, quantite, prixUnitaireHT, TVA) VALUES (?, ?, ?, ?, ?)";
$stmtProduit = $conn->prepare($queryProduits);

if (!$stmtProduit) {
    $conn->rollback(); 
    echo json_encode(["success" => false, "message" => "Erreur préparation requête produit"]);
    exit;
}

foreach ($produits as $prod) {
    $stmtProduit->bind_param("isidd", $idProforma, $prod["codeProduit"], $prod["quantite"], $prod["prixUnitaireHT"], $prod["TVA"]);
    if (!$stmtProduit->execute()) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout d'un produit"]);
        exit; 
    }
}

// 3️⃣ Mise à jour du totalHT
$queryUpdateHT = "UPDATE proforma 
                  SET totalHT = (SELECT SUM(pp.quantite * pp.prixUnitaireHT) FROM proforma_produit pp WHERE pp.idProforma = ?) 
                  WHERE idProforma = ?";
$stmtUpdateHT = $conn->prepare($queryUpdateHT);
$stmtUpdateHT->bind_param("ii", $idProforma, $idProforma);
if (!$stmtUpdateHT->execute()) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Erreur mise à jour totalHT"]);
    exit;
}

// 4️⃣ Mise à jour de la TVA
$queryUpdateTVA = "UPDATE proforma
                   SET TVA = (SELECT SUM(pp.quantite * pp.prixUnitaireHT * (pp.TVA / 100)) FROM proforma_produit pp WHERE pp.idProforma = ?) 
                   WHERE idProforma = ?";
$stmtUpdateTVA = $conn->prepare($queryUpdateTVA);
$stmtUpdateTVA->bind_param("ii", $idProforma, $idProforma);
if (!$stmtUpdateTVA->execute()) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Erreur mise à jour TVA"]);
    exit;
}

// 5️⃣ Mise à jour de totalTTC
$queryUpdateTTC = "UPDATE proforma 
                   SET totalTTC = totalHT + TVA 
                   WHERE idProforma = ?";
$stmtUpdateTTC = $conn->prepare($queryUpdateTTC);
$stmtUpdateTTC->bind_param("i", $idProforma);
if (!$stmtUpdateTTC->execute()) {
    $conn->rollback();  
    echo json_encode(["success" => false, "message" => "Erreur mise à jour totalTTC"]);
    exit;
}

$conn->commit(); // ✅ Tout s'est bien passé, on valide la transaction

echo json_encode(["success" => true, "message" => "Proforma et produits ajoutés avec succès", "idProforma" => $idProforma]);

$conn->close();
?>
