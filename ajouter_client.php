<?php
include 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Débogage : Vérifier si on reçoit des données
$rawData = file_get_contents("php://input");
file_put_contents("log.txt", "Données reçues : " . $rawData . "\n", FILE_APPEND);
$data = json_decode($rawData, true);

if (isset($data['codeClient']) && isset($data['nomClient']) &&
    isset($data['prenomClient']) && isset($data['dateNaissance']) &&
    isset($data['email']) && isset($data['telephone'])) {

    $codeClient = $conn->real_escape_string($data['codeClient']);
    $nomClient = $conn->real_escape_string($data['nomClient']);
    $prenomClient = $data['prenomClient'];
    $dateNaissance = $data['dateNaissance'];
    $email = $data['email'];
    $telephone = $data['telephone'];

    $sql = "INSERT INTO client (codeClient, nomClient, prenomClient, dateNaissance, email, telephone) VALUES ('$codeClient', '$nomClient', '$prenomClient', '$dateNaissance', '$email', '$telephone')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Client ajouté"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur SQL : " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Données manquantes"]);
}

$conn->close();
?>
