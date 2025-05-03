<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $stmt = $conn->prepare("DELETE FROM events WHERE id = :id");
    $stmt->bindParam(':id', $event_id);
    $stmt->execute();
    header("Location: index.php?success=1");
    exit();
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>