<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
} 

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "gestion_commandes");

// Récupération des données envoyées
$data = json_decode(file_get_contents("php://input"));

$email = $conn->real_escape_string($data->email);
$password = $data->password;

// Recherche de l'utilisateur
$result = $conn->query("SELECT * FROM users WHERE email = '$email'");

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        echo json_encode([
            "success" => true,
            "email" => $user['email']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Mot de passe incorrect"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Utilisateur introuvable"]);
}

$conn->close();
?> 