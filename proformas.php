<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'db.php';

$sql = "SELECT * FROM proforma";
$result = $conn->query($sql);

$proformas = [];
while ($row = $result->fetch_assoc()) {
    $proformas[] = $row;
}

echo json_encode($proformas);
?>
