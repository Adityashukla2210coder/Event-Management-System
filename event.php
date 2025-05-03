<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $stmt = $conn->prepare("SELECT title, description, event_date, location FROM events WHERE id = :id");
    $stmt->bindParam(':id', $event_id);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$event) {
        die("Event not found.");
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['title']); ?> - EventSphere</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-poppins">
    <header class="bg-indigo-600 text-white py-6">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold">EventSphere</h1>
            <nav class="mt-4">
                <a href="index.php" class="text-white hover:underline mx-4">Home</a>
                <a href="admin/login.php" class="text-white hover:underline mx-4">Admin Login</a>
            </nav>
        </div>
    </header>

    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-indigo-600 mb-6"><?php echo htmlspecialchars($event['title']); ?></h2>
            <p class="text-gray-600"><?php echo htmlspecialchars($event['description']); ?></p>
            <p class="text-gray-500 mt-2"><strong>Date:</strong> <?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
            <p class="text-gray-500"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
            <h3 class="text-xl font-semibold mt-6 mb-4">Register for this Event</h3>
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <p class="text-green-500 mb-4">Registration successful!</p>
            <?php endif; ?>
            <form id="register-form" action="register.php" method="POST" class="bg-white p-6 rounded-lg shadow-lg max-w-lg">
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" id="name" name="name" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="w-full p-2 border rounded" required>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Register</button>
            </form>
        </div>
    </section>

    <footer class="bg-indigo-600 text-white py-6">
        <div class="container mx-auto px-4 text-center">
            <p>© 2025 EventSphere. All rights reserved.</p>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>