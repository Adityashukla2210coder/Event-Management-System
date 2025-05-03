<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $event_id = $_POST['event_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $stmt = $conn->prepare("INSERT INTO registrations (event_id, name, email) VALUES (:event_id, :name, :email)");
        $stmt->bindParam(':event_id', $event_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        header("Location: event.php?id=$event_id&success=1");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>