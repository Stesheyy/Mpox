<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mpox";
$port = "3306";
// Create connection
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_GET['table'])) {
    $id = $_GET['id'];
    $table = $_GET['table'];

    // Check if the table exists in the allowed list
    $validTables = ['contact', 'report']; // Add any other tables that should be updated
    if (in_array($table, $validTables)) {
        // Update the database to mark the message as 'read'
        $sql = "UPDATE $table SET status = 'read' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: healthcare_dashboard.php?status=read"); // Redirect back to the dashboard
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Invalid table name.";
    }

    $conn->close();
} else {
    echo "Missing parameters.";
}
?>
