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
$password = password_hash($data->password, PASSWORD_DEFAULT);

// Vérifie si l'utilisateur existe déjà
$check = $conn->query("SELECT * FROM users WHERE email = '$email'");
if ($check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Cet email est déjà utilisé"]);
    exit; 
}

// Insère le nouvel utilisateur
$stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $password);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Inscription réussie"]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur lors de l'inscription"]);
}

$conn->close();  
?> 