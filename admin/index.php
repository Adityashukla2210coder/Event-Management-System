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

    // Fetch events
    $stmt = $conn->prepare("SELECT id, title, description, event_date, location FROM events ORDER BY event_date");
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch users registered to events
    $users_by_event = [];
    $user_stmt = $conn->prepare("SELECT name, email, event_id FROM registrations");
    $user_stmt->execute();
    $users = $user_stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($users as $user) {
        $users_by_event[$user['event_id']][] = $user;
    }

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
    <title>Admin Dashboard - EventSphere</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-poppins">
    <header class="bg-indigo-600 text-white py-6">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold">Admin Dashboard</h1>
            <nav class="mt-4">
                <a href="add_event.php" class="text-white hover:underline mx-4">Add Event</a>
                <a href="logout.php" class="text-white hover:underline mx-4">Logout</a>
            </nav>
        </div>
    </header>

    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-indigo-600 mb-6">Manage Events</h2>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <?php if (empty($events)): ?>
                    <p class="text-gray-600">No events found.</p>
                <?php else: ?>
                    <table class="w-full table-auto mb-6">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2">Title</th>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2">Location</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $event): ?>
                                <tr class="border-b">
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($event['title']); ?></td>
                                    <td class="border px-4 py-2"><?php echo date('F j, Y', strtotime($event['event_date'])); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($event['location']); ?></td>
                                    <td class="border px-4 py-2">
                                        <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="text-indigo-600 hover:underline">Edit</a>
                                        <a href="delete_event.php?id=<?php echo $event['id']; ?>" class="text-red-600 hover:underline ml-2" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="bg-gray-50 px-4 py-2">
                                        <?php if (!empty($users_by_event[$event['id']])): ?>
                                            <strong>Registered Users:</strong>
                                            <ul class="list-disc list-inside text-sm text-gray-700 mt-2">
                                                <?php foreach ($users_by_event[$event['id']] as $user): ?>
                                                    <li><?php echo htmlspecialchars($user['name']); ?> (<?php echo htmlspecialchars($user['email']); ?>)</li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <span class="text-gray-500">No users registered.</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="bg-indigo-600 text-white py-6">
        <div class="container mx-auto px-4 text-center">
            <p>© 2025 EventSphere. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
