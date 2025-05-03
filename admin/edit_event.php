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
    $stmt = $conn->prepare("SELECT title, description, event_date, location FROM events WHERE id = :id");
    $stmt->bindParam(':id', $event_id);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$event) {
        die("Event not found.");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $event_date = $_POST['event_date'];
        $location = $_POST['location'];
        $stmt = $conn->prepare("UPDATE events SET title = :title, description = :description, event_date = :event_date, location = :location WHERE id = :id");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':event_date', $event_date);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':id', $event_id);
        $stmt->execute();
        header("Location: index.php?success=1");
        exit();
    }
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - EventSphere</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-poppins">
    <header class="bg-indigo-600 text-white py-6">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold">EventSphere Admin</h1>
            <nav class="mt-4">
                <a href="index.php" class="text-white hover:underline mx-4">Dashboard</a>
                <a href="logout.php" class="text-white hover:underline mx-4">Logout</a>
            </nav>
        </div>
    </header>

    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-indigo-600 mb-6">Edit Event</h2>
            <?php if (isset($error)): ?>
                <p class="text-red-500 mb-4"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form action="edit_event.php?id=<?php echo $event_id; ?>" method="POST" class="bg-white p-6 rounded-lg shadow-lg max-w-lg">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700">Event Title</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea id="description" name="description" class="w-full p-2 border rounded" rows="4"><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="event_date" class="block text-gray-700">Event Date</label>
                    <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="location" class="block text-gray-700">Location</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" class="w-full p-2 border rounded" required>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update Event</button>
            </form>
        </div>
    </section>

    <footer class="bg-indigo-600 text-white py-6">
        <div class="container mx-auto px-4 text-center">
            <p>© 2025 EventSphere. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>