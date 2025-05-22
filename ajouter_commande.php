<?php
include 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Lire les données JSON envoyées
$data = json_decode(file_get_contents("php://input"), true);

// Debug : écrire les données reçues dans un fichier log
file_put_contents("debug_commande.txt", print_r($data, true), FILE_APPEND);

if (!isset($data["codeClient"], $data["dateCommande"], $data["produits"])) {
    echo json_encode(["success" => false, "message" => "Données incomplètes"]);
    exit;
}

$codeClient = $data["codeClient"];
$dateCommande = $data["dateCommande"];
$produits = $data["produits"];

$conn->begin_transaction(); // 🔹 Démarrer une transaction

// 1️⃣ Insérer la commande
$query = "INSERT INTO commande (codeClient, dateCommande, totalHT, totalTTC, TVA) VALUES (?, ?, 0, 0, 0)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Erreur préparation requête commande"]);
    exit;
}

$stmt->bind_param("ss", $codeClient, $dateCommande);

if (!$stmt->execute()) {
    $conn->rollback(); // ❌ Annuler tout si erreur
    echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout de la commande"]);
    exit;
}

$idCommande = $stmt->insert_id; // Récupérer l'ID de la commande créée

// 2️⃣ Insérer les produits
$queryProduits = "INSERT INTO commande_produit (idCommande, codeProduit, quantite, prixUnitaireHT, TVA) VALUES (?, ?, ?, ?, ?)";
$stmtProduit = $conn->prepare($queryProduits);

if (!$stmtProduit) {
    $conn->rollback(); 
    echo json_encode(["success" => false, "message" => "Erreur préparation requête produit"]);
    exit;
}

foreach ($produits as $prod) {
    $stmtProduit->bind_param("isidd", $idCommande, $prod["codeProduit"], $prod["quantite"], $prod["prixUnitaireHT"], $prod["TVA"]);
    if (!$stmtProduit->execute()) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout d'un produit"]);
        exit;
    }
}

// 3️⃣ Mise à jour de totalHT
$queryUpdateHT = "UPDATE commande 
                  SET totalHT = (SELECT SUM(cp.quantite * cp.prixUnitaireHT) FROM commande_produit cp WHERE cp.idCommande = ?) 
                  WHERE idCommande = ?";
$stmtUpdateHT = $conn->prepare($queryUpdateHT);
$stmtUpdateHT->bind_param("ii", $idCommande, $idCommande);
if (!$stmtUpdateHT->execute()) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Erreur mise à jour totalHT"]);
    exit;
}

// 4️⃣ Mise à jour de la TVA
$queryUpdateTVA = "UPDATE commande 
                   SET TVA = (SELECT SUM(cp.quantite * cp.prixUnitaireHT * (cp.TVA / 100)) FROM commande_produit cp WHERE cp.idCommande = ?) 
                   WHERE idCommande = ?";
$stmtUpdateTVA = $conn->prepare($queryUpdateTVA);
$stmtUpdateTVA->bind_param("ii", $idCommande, $idCommande);
if (!$stmtUpdateTVA->execute()) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Erreur mise à jour TVA"]);
    exit;
}

// 5️⃣ Mise à jour de totalTTC
$queryUpdateTTC = "UPDATE commande 
                   SET totalTTC = totalHT + TVA 
                   WHERE idCommande = ?";
$stmtUpdateTTC = $conn->prepare($queryUpdateTTC);
$stmtUpdateTTC->bind_param("i", $idCommande);
if (!$stmtUpdateTTC->execute()) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Erreur mise à jour totalTTC"]);
    exit;
}

$conn->commit(); // ✅ Tout s'est bien passé, on valide la transaction

echo json_encode(["success" => true, "message" => "Commande et produits ajoutés avec succès", "idCommande" => $idCommande]);

$conn->close();
?>
