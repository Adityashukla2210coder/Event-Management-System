<?php
ob_start();
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $username = $_POST['username'];
        $password = $_POST['password'];
        $stmt = $conn->prepare("SELECT password FROM admins WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid credentials.";
        }
    } catch(PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - EventSphere</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-poppins flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold text-indigo-600 mb-6 text-center">Admin Login</h2>
        <?php if (isset($error)): ?>
            <p class="text-red-500 mb-4"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Login</button>
        </form>
    </div>
</body>
</html>