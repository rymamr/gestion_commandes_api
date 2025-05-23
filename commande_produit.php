<?php
header("Access-Control-Allow-Origin: *");  // Autorise toutes les origines (*)
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Gérer la requête OPTIONS (CORS Preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(["message" => "Requête OPTIONS reçue"]);
    exit();
} 

include 'db.php';
 
// Fonction pour envoyer une réponse JSON cohérente
function sendResponse($success, $message, $data = null) {
    $response = ["success" => $success, "message" => $message];
    if ($data !== null) $response["data"] = $data;
    echo json_encode($response);
}

// 📌 Ajouter un produit à une commande
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->idCommande) && !empty($data->codeProduit) && isset($data->quantite) && isset($data->prixUnitaireHT) && isset($data->TVA)) {
        $query = "INSERT INTO commande_produit (idCommande, codeProduit, quantite, prixUnitaireHT, TVA) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            sendResponse(false, "Erreur SQL: " . $conn->error);
            exit();
        }

        $stmt->bind_param("isid", $data->idCommande, $data->codeProduit, $data->quantite, $data->prixUnitaireHT, $data->TVA);
        
        if ($stmt->execute()) {
            sendResponse(true, "Produit ajouté à la commande");
        } else {
            sendResponse(false, "Échec de l'ajout du produit");
        }
    } else {
        sendResponse(false, "Données manquantes");
    }
}

// 📌 Lister les produits d'une commande
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['idCommande'])) {
        $idCommande = $_GET['idCommande'];

        $query = "SELECT cp.idCommande, p.codeProduit, p.designation, cp.quantite, cp.prixUnitaireHT, cp.TVA 
                  FROM commande_produit cp 
                  JOIN produit p ON cp.codeProduit = p.codeProduit 
                  WHERE cp.idCommande = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            sendResponse(false, "Erreur SQL: " . $conn->error);
            exit();
        }

        $stmt->bind_param("i", $idCommande);
        $stmt->execute();
        $result = $stmt->get_result();

        $produits = [];
        while ($row = $result->fetch_assoc()) {
            $produits[] = $row;
        }

        sendResponse(true, "Produits récupérés avec succès", $produits);
    } else {
        sendResponse(false, "idCommande non fourni");
    }
}

// 📌 Supprimer un produit d'une commande
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->idCommande) && !empty($data->codeProduit)) {
        $query = "DELETE FROM commande_produit WHERE idCommande = ? AND codeProduit = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            sendResponse(false, "Erreur SQL: " . $conn->error);
            exit();
        }

        $stmt->bind_param("is", $data->idCommande, $data->codeProduit);

        if ($stmt->execute()) {
            sendResponse(true, "Produit supprimé de la commande");
        } else {
            sendResponse(false, "Échec de la suppression du produit");
        }
    } else {
        sendResponse(false, "Données manquantes");
    }
}

$conn->close();
?>
