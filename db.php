<?php
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "gestion_commandes";

// Désactiver les erreurs PHP à l'affichage (utile en prod)
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4"); // Éviter les problèmes d'encodage

    // Vérifier la connexion
    if ($conn->connect_error) {
        throw new Exception("Erreur de connexion : " . $conn->connect_error);
    }

} catch (Exception $e) {
    die(json_encode(["error" => $e->getMessage()])); // Retourne l'erreur en JSON
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Active les erreurs SQL

?>
