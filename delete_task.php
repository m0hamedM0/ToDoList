<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "Error: User not logged in";
    exit;
}

$servername = "127.0.0.1";  // Localhost IP address
$username = "root";  // MySQL username
$password = "";  // MySQL password (empty in this case)
$dbname = "todolistesnnl";  // Your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Aufgabe erfolgreich gelöscht!";
    } else {
        echo "Fehler: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    header("Location: todolist.php");
    exit();
}
?>