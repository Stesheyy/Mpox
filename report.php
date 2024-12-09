<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mpox";
$port = "3306";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Capture form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$location = isset($_POST['location']) ? trim($_POST['location']) : '';
$symptoms_observed = isset($_POST['symptoms_observed']) ? trim($_POST['symptoms_observed']) : '';
$date_of_exposure = isset($_POST['date_of_exposure']) && !empty($_POST['date_of_exposure']) ? $_POST['date_of_exposure'] : '0000000';
$additional_info = isset($_POST['additional_info']) ? trim($_POST['additional_info']) : '';

// Handle empty date_of_exposure
if (empty($date_of_exposure)) {
    $date_of_exposure = null;
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO report (name, email, location, symptoms_observed, date_of_exposure, additional_info) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $email, $location, $symptoms_observed, $date_of_exposure, $additional_info);

// Feedback message
$feedbackMessage = '';
$feedbackClass = 'error'; // Default class for error

if ($stmt->execute()) {
    $feedbackMessage = "Report submitted successfully! You will be notified. THANK YOU :)";
    $feedbackClass = 'success';
} else {
    $feedbackMessage = "Error: " . $stmt->error;
}

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
            background-color: #00008B;
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
