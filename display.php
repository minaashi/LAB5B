<?php
require 'session.php';  // Ensure session is started
require 'db.php';        // Ensure the database connection is established

// Check if 'accessLevel' is set in the session and if the user is a lecturer
if (!isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] !== 'Lecturer') {
    // Redirect user to the login page if they are not a lecturer or not logged in
    header("Location: login.php");
    exit;  // Stop execution if the user is not a lecturer or session doesn't have accessLevel
}

// Fetch and display students
$result = $conn->query("SELECT id, matric, name, accessLevel FROM student");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Student List</title>
</head>
<body>
    <div class="container">
        <h2>Student List</h2>
        <table>
            <tr><th>Matric</th><th>Name</th><th>Access Level</th><th>Actions</th></tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['matric']); ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['accessLevel']); ?></td>
                <td>
                    <a href="update.php?id=<?= $row['id']; ?>">Update</a> |
                    <a href="delete.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
