<?php
require 'db.php';  // Ensure the database connection is set up correctly
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = trim($_POST['matric']);
    $password = trim($_POST['password']);

    // Fetch the student's hashed password from the database
    $stmt = $conn->prepare("SELECT id, password, accessLevel FROM student WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password, $accessLevel);
    $stmt->fetch();
    $stmt->close();

    // Check if the password matches the hash in the database
    if ($id && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;  // Store the user ID in the session
        $_SESSION['accessLevel'] = $accessLevel;  // Store the access level (student or lecturer)
        header("Location: display.php");
        exit;
    } else {
        $error = "Invalid matric or password.";  // Password does not match
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <form method="POST" action="">
            <h2>Login</h2>
            <label>Matric:</label><input type="text" name="matric" required><br>
            <label>Password:</label><input type="password" name="password" required><br>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>
        <a href="register.php">Register</a>
    </div>
</body>
</html>
