<?php
require 'session.php';  // Ensure session is started
require 'db.php';        // Ensure the database connection is established

// Check if the user has the 'Lecturer' access level
if ($_SESSION['accessLevel'] !== 'Lecturer') {
    echo "Access Denied!";
    exit;
}

// If it's a GET request, fetch the student's data for updating
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];
    // Retrieve student data based on ID
    $stmt = $conn->prepare("SELECT matric, name, email, accessLevel FROM student WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($matric, $name, $email, $accessLevel);
    $stmt->fetch();
    $stmt->close();
}

// If it's a POST request, update the student's data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $accessLevel = $_POST['accessLevel']; // Lecturer or Student

    // Validate inputs
    $name = htmlspecialchars($name);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Update the student's data
        $stmt = $conn->prepare("UPDATE student SET matric = ?, name = ?, email = ?, accessLevel = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $matric, $name, $email, $accessLevel, $id);
        if ($stmt->execute()) {
            header("Location: display.php");
            exit;
        } else {
            echo "Update failed.";
        }
        $stmt->close();
    } else {
        echo "Invalid email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Update Student</title>
</head>
<body>
    <div class="container">
        <form method="POST" action="update.php">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id); ?>">
            <h2>Update Student</h2>
            <label>Matric:</label><input type="text" name="matric" value="<?= htmlspecialchars($matric); ?>" required><br>
            <label>Name:</label><input type="text" name="name" value="<?= htmlspecialchars($name); ?>" required><br>
            <label>Email:</label><input type="email" name="email" value="<?= htmlspecialchars($email); ?>" required><br>
            <label>Access Level:</label>
            <select name="accessLevel" required>
                <option value="Lecturer" <?= $accessLevel == 'Lecturer' ? 'selected' : ''; ?>>Lecturer</option>
                <option value="Student" <?= $accessLevel == 'Student' ? 'selected' : ''; ?>>Student</option>
            </select><br>
            <button type="submit">Update</button>
        </form>
        <a href="display.php">Back to User List</a>
    </div>
</body>
</html>
