<?php
require 'session.php';
require 'db.php';

if ($_SESSION['accessLevel'] !== 'Lecturer') {
    echo "Access Denied!";
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM student WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: display.php");
        exit;
    } else {
        echo "Error deleting student.";
    }
    $stmt->close();
}
?>
