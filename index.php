<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT id, title, description, event_date, location FROM events WHERE event_date >= CURDATE() ORDER BY event_date");
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    $events = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventSphere - Discover Events</title>
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
            <h2 class="text-2xl font-bold text-indigo-600 mb-6">Upcoming Events</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($events)): ?>
                    <p class="text-gray-600">No upcoming events.</p>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <div class="bg-white p-6 rounded-lg shadow-lg">
                            <h3 class="text-xl font-semibold text-indigo-600"><?php echo htmlspecialchars($event['title']); ?></h3>
                            <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($event['description']); ?></p>
                            <p class="text-gray-500 mt-2"><strong>Date:</strong> <?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
                            <p class="text-gray-500"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                            <a href="event.php?id=<?php echo $event['id']; ?>" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">View Details</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
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