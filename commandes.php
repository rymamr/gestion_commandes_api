<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'db.php';

header("Content-Type: application/json");

$query = "SELECT * FROM commande";
$result = $conn->query($query);

$commandes = [];

while ($row = $result->fetch_assoc()) {
    $commandes[] = $row;
}

echo json_encode($commandes);
?>
