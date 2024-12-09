<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mpox";
$port = "3306";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phone = isset($_POST['phone_number']) && !empty($_POST['phone_number']) ? $_POST['phone_number'] : '0000000';
$message = isset($_POST['message']) ? $_POST['message'] : '';

// Prepare and execute SQL query
$sql = "INSERT INTO contact (name, email, phone_number, message) VALUES (?, ?, ?, ?)";

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die("Error preparing the statement: " . $conn->error);
}

// Bind the parameters correctly
$stmt->bind_param("ssss", $name, $email, $phone, $message);

// Feedback message
$feedbackMessage = '';
$feedbackClass = 'error'; // Default class for error

try {
    if ($stmt->execute()) {
        $feedbackMessage = "Message sent successfully, We will get in touch soon, THANK YOU!";
        $feedbackClass = 'success';
    } else {
        $feedbackMessage = "Error: " . $stmt->error;
    }
} catch (Exception $e) {
    $feedbackMessage = "Error: " . $e->getMessage();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .feedback-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .feedback {
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 16px;
        }
        .feedback.success {
            background-color: #9D5164;
            color: white;
        }
        .feedback.error {
            background-color: #f44336;
            color: white;
        }
        .back-button {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="feedback-container">
        <div class="feedback <?php echo $feedbackClass; ?>">
            <?php echo htmlspecialchars($feedbackMessage); ?>
            <?php
            // Check if HTTP_REFERER is set
            $previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'default_page.php';
            ?>
            <a href="<?php echo $previousPage; ?>" class="back-button">Go Back</a>
        </div>
    </div>
</body>
</html>
