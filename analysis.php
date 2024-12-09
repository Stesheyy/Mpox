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

// Retrieve form data using $_POST with validation
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$dob = isset($_POST['dob']) ? $_POST['dob'] : '';  // Ensure this is a valid date format (YYYY-MM-DD)
$email = isset($_POST['email']) ? $_POST ['email'] : '';
$phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
$todays_date = isset($_POST['todays-date']) ? $_POST['todays-date'] : '';  // Same as above
$q1 = isset($_POST['q1']) ? (int)$_POST['q1'] : null;
$q2 = isset($_POST['q2']) ? (int)$_POST['q2'] : null;
$q3 = isset($_POST['q3']) ? (int)$_POST['q3'] : null;
$q4 = isset($_POST['q4']) ? (int)$_POST['q4'] : null;
$q5 = isset($_POST['q5']) ? (int)$_POST['q5'] : null;
$q6 = isset($_POST['q6']) ? (int)$_POST['q6'] : null;
$q7 = isset($_POST['q7']) ? (int)$_POST['q7'] : null;
$q8 = isset($_POST['q8']) ? (int)$_POST['q8'] : null;
$q9 = isset($_POST['q9']) ? (int)$_POST['q9'] : null;
$additional_symptoms = isset($_POST['additional_symptoms']) ? trim($_POST['additional_symptoms']) : '';

// Prepare an SQL query using prepared statements
$sql = "INSERT INTO analysis (name, dob, email, phone_number, todays_date, q1, q2, q3, q4, q5, q6, q7, q8, q9, additional_symptoms) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Prepare the statement
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing the statement: " . $conn->error);
}

// Bind the form data to the prepared statement
$stmt->bind_param(
    "sssssiiiiiiiiis",  // Data types: 3 strings (s) for name, dob, and todays_date; 9 integers (i) for the q1-q9; 1 string (s) for additional_symptoms
    $name, 
    $dob, 
    $email,
    $phone_number,
    $todays_date, 
    $q1, 
    $q2, 
    $q3, 
    $q4, 
    $q5, 
    $q6, 
    $q7, 
    $q8, 
    $q9, 
    $additional_symptoms
);

// Execute the statement and provide feedback
$feedbackMessage = '';
if ($stmt->execute()) {
    $feedbackMessage = "Thank you, your assessment has been submitted successfully. We will get back to you soon.";
    $feedbackClass = "success";
} else {
    $feedbackMessage = "Error: " . $stmt->error;
    $feedbackClass = "error";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
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
            // Check if HTTP_REFERER is set to allow going back to the previous page
            $previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'default_page.php';
            ?>
            <a href="<?php echo $previousPage; ?>" class="back-button">Go Back</a>
        </div>
    </div>
</body>
</html>
