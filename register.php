<?php
require 'db.php';  // Ensure the database connection is set up correctly

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $accessLevel = $_POST['accessLevel'];  // This is 'lecturer' or 'student'
    $password = $_POST['password'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address!";
        exit;
    }

    // Hash the password before storing
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Insert student into the database
    $stmt = $conn->prepare("INSERT INTO student (matric, name, email, accessLevel, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $matric, $name, $email, $accessLevel, $passwordHash);
    if ($stmt->execute()) {
        header("Location: login.php?register=success");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <form method="POST" action="">
            <h2>Register</h2>
            <label>Matric:</label><input type="text" name="matric" required><br>
            <label>Name:</label><input type="text" name="name" required><br>
            <label>Email:</label><input type="email" name="email" required><br>
            <label>Access Level:</label>
            <select name="accessLevel" required>
                <option value="Lecturer">Lecturer</option>
                <option value="Student">Student</option>
            </select><br>
            <label>Password:</label><input type="password" name="password" required><br>
            <button type="submit">Register</button>
        </form>
        <a href="login.php">Login</a>
    </div>
</body>
</html>
