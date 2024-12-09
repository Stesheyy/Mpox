<?php
session_start();

// Check if the user is logged in and has the healthcare role
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Healthcare') {
    header('Location: login.html');
    exit();
}

// Database connection
$host = 'localhost';
$db = 'mpox';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch data from the database (example for the "analysis" table)
$stmt = $pdo->query("SELECT * FROM analysis");
$analyses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch data from the "contact" table
$stmtContact = $pdo->query("SELECT * FROM contact");
$contacts = $stmtContact->fetchAll(PDO::FETCH_ASSOC);

// Fetch data from the "report" table
$stmtReport = $pdo->query("SELECT * FROM report");
$reports = $stmtReport->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        title{
            color: #f8f9fa;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #800020;
            padding: 1rem;
            color: white;
            text-align: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
        .container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #800020;
            color: white;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            text-align: center;
            margin-top: 20px;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        h2 {
            color: white;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>Healthcare Dashboard</h2>
        <a href="healthcare_dashboard.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <!-- Analyses Table -->
        <h3>Analysis Data</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Email</th>
                    <th>Phone number</th>
                    <th>Date</th>
                    <th>Additional Symptoms</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach ($analyses as $analysis): ?>
                    <tr>
                        <td><?= htmlspecialchars($analysis['Name']) ?></td>
                        <td><?= htmlspecialchars($analysis['dob']) ?></td>
                        <td><?= htmlspecialchars($analysis['email']) ?></td>
                        <td><?= htmlspecialchars($analysis['phone_number']) ?></td>
                        <td><?= htmlspecialchars($analysis['todays_date']) ?></td>
                        <td><?= htmlspecialchars($analysis['additional_symptoms']) ?></td>
                        
                       
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Contacts Table -->
        <h3>Contact Information</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Message</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars($contact['Name']) ?></td>
                        <td><?= htmlspecialchars($contact['Email']) ?></td>
                        <td><?= htmlspecialchars($contact['phone_number']) ?></td>
                        <td><?= htmlspecialchars($contact['Message']) ?></td>
                        

                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Reports Table -->
        <h3>Reports</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Location</th>
                    <th>Symptoms</th>
                    <th>Date of Exposure</th>
                    <th>Additional Info</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?= htmlspecialchars($report['name']) ?></td>
                        <td><?= htmlspecialchars($report['email']) ?></td>
                        <td><?= htmlspecialchars($report['location']) ?></td>
                        <td><?= htmlspecialchars($report['symptoms_observed']) ?></td>
                        <td><?= htmlspecialchars($report['date_of_exposure']) ?></td>
                        <td><?= htmlspecialchars($report['additional_info']) ?></td>
                        

                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
<!-- Print Report Button -->
<button id="printReport" onclick="printReport()" style="background-color: blue; color: white; padding: 10px 20px; border: none; margin-bottom: 20px;">
    Print Report
</button>
        <button class="logout-btn" onclick="window.location.href='logout.php';">Logout</button>
    </div>

        </div>
    </div>
    <script>
function printReport() {
    // Select the sidebar and any other elements to hide
    const sidebar = document.querySelector('div[style*="width: 150px"]'); // Sidebar
    const addUserForm = document.querySelector('form'); // Add user form

    // Backup the original display styles
    const sidebarOriginalDisplay = sidebar ? sidebar.style.display : null;
    const addUserFormOriginalDisplay = addUserForm ? addUserForm.style.display : null;

    // Hide unwanted elements for printing
    if (sidebar) sidebar.style.display = 'none';
    if (addUserForm) addUserForm.style.display = 'none';

    // Trigger the print dialog
    window.print();

    // Restore the original display styles after printing
    if (sidebar) sidebar.style.display = sidebarOriginalDisplay;
    if (addUserForm) addUserForm.style.display = addUserFormOriginalDisplay;
}
</script>



</body>
</html>
