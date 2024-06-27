<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script type='text/javascript'>alert('Error: User not logged in'); window.location.href='login.html';</script>";
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

// Fetch tasks from the database
$sql = "SELECT id, task_type, task_name, task_date, completed FROM tasks";
$result = $conn->query($sql);

$tasks = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tasks[] = [
            'title' => $row['task_name'],
            'start' => $row['task_date'],
            'color' => $row['completed'] ? 'green' : 'red',
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Meine Webseite</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <li class="nav-item">
                    <a class="nav-link" href="create_task.php">Create New Task</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="todolist.php">Show Tasks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="calendar.php">Kalender</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.html">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="registration.php">Registrieren</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<div class="container">
    <h1>Kalender</h1>
    <div id='calendar'></div>
</div>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: <?php echo json_encode($tasks); ?>
    });
    calendar.render();
});
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
