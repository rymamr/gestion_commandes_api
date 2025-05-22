<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'db.php';

// 📌 Ajouter un produit à une proforma
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->idProforma) && !empty($data->codeProduit) && isset($data->quantite) && isset($data->prixUnitaireHT) && isset($data->TVA)) {
        $query = "INSERT INTO proforma_produit (idProforma, codeProduit, quantite, prixUnitaireHT, TVA) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isidd", $data->idProforma, $data->codeProduit, $data->quantite, $data->prixUnitaireHT, $data->TVA);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Produit ajouté à la proforma"]);
        } else {
            echo json_encode(["error" => "Échec de l'ajout"]);
        }
    } else {
        echo json_encode(["error" => "Données manquantes"]);
    }
}

// 📌 Lister les produits d'une proforma
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['idProforma'])) {
    $idProforma = $_GET['idProforma'];
    $query = "SELECT p.codeProduit, p.designation, pp.quantite, pp.prixUnitaireHT, pp.TVA 
              FROM proforma_produit pp 
              JOIN produit p ON pp.codeProduit = p.codeProduit 
              WHERE pp.idProforma = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idProforma);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $produits = [];
    while ($row = $result->fetch_assoc()) {
        $produits[] = $row;
    }

    echo json_encode($produits);
}

// 📌 Supprimer un produit d'une proforma
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->idProforma) && !empty($data->codeProduit)) {
        $query = "DELETE FROM proforma_produit WHERE idProforma = ? AND codeProduit = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $data->idProforma, $data->codeProduit);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Produit supprimé de la proforma"]);
        } else {
            echo json_encode(["error" => "Échec de la suppression"]);
        }
    } else {
        echo json_encode(["error" => "Données manquantes"]);
    }
}

$conn->close();
?>
