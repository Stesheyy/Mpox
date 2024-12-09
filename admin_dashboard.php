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

// Handle add user
if (isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role'];

    $addUserSql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($addUserSql);
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    $stmt->execute();
}

// Handle delete user
if (isset($_GET['delete_id'])) {
    $userId = $_GET['delete_id'];
    $deleteSql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}

// Handle edit user update
if (isset($_POST['update_user'])) {
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // If password is provided, hash it before updating
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $updateSql = "UPDATE users SET name = ?, email = ?, role = ?, password = ? WHERE Id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ssssi", $name, $email, $role, $password, $userId);
    } else {
        // If password is empty, don't update it
        $updateSql = "UPDATE users SET name = ?, email = ?, role = ? WHERE Id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("sssi", $name, $email, $role, $userId);
    }
    $stmt->execute();
}

// Fetch user role distribution
$roleCounts = [];
$roleQuery = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
$roleResults = $conn->query($roleQuery);

while ($row = $roleResults->fetch_assoc()) {
    $roleCounts[$row['role']] = $row['count'];
}

// Prepare data for Chart.js
$roles = json_encode(array_keys($roleCounts)); // Role names (Admin, Healthcare Personnel, Patient)
$counts = json_encode(array_values($roleCounts)); // Count of each role


// Fetch user data
$userSql = "SELECT * FROM users";
$users = $conn->query($userSql)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Modal and overlay styles */
        #edit-form {
            display: none;
            position: fixed;
            top: 20%;
            left: 30%;
            width: 40%;
            background-color: white;
            padding: 20px;
            border: 2px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 20px;
        }
        .form-container, .chart-container {
            width: 48%;
        }
        .chart-container {
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        canvas {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0;">

    <!-- Sidebar -->
    <div style="width: 150px; height: 100vh; background: #800020; color: white; position: fixed; top: 0; left: 0; padding: 20px;">
        <h3 style="margin-top: 0;">Dashboard</h3>
        <ul style="list-style: none; padding: 0;">
            <li><a href="admin_dashboard.php" style="color: white; text-decoration: none;">Home</a></li>
            <li><a href="logout.php" style="color: white; text-decoration: none;">Logout</a></li>
        </ul>
        <button id="printReport" onclick="printReport()" style="background-color: blue; color: white; padding: 10px 20px; border: none; margin-bottom: 20px;">
    Print Report
</button>
    </div>

    <!-- Main Content -->
    <div style="margin-left: 220px; padding: 20px;">
        <h1>Welcome to the Admin Dashboard</h1>

        <!-- Add User Form -->
         <div class="container">
            <div class="form-container">
        <h3>Add New User</h3>
        <form method="POST" action="admin_dashboard.php">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br><br>
            
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <label for="role">Role:</label><br>
            <select id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="healthcare">Healthcare Personnel</option>
                <option value="patient">Patient</option>
            </select><br><br>

            <button type="submit" name="add_user" style="background-color: green; color: white; padding: 10px 20px; border: none;">Add User</button>
        </form>
        </div>
        <div class="chart-container">
        <h3>User Distribution by Role</h3>
<canvas id="userChart" width="400" height="200"></canvas>
</div>
</div>
        <!-- User Management Section -->
        <h3>User Management</h3>
        <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: #f2f2f2;">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <!-- Edit User Button -->
                            <button onclick="editUser(<?= $user['Id'] ?>, '<?= htmlspecialchars($user['name']) ?>', '<?= htmlspecialchars($user['email']) ?>', '<?= htmlspecialchars($user['role']) ?>')" style="background-color: blue; color: white; padding: 5px 10px; border: none; cursor: pointer;">Edit</button>
                            <!-- Delete User Button -->
                            <a href="admin_dashboard.php?delete_id=<?= $user['Id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">
                                <button style="background-color: red; color: white; padding: 5px 10px; border: none; cursor: pointer;">Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <!-- Edit User Modal -->
    <div id="edit-form">
        <h2>Edit User</h2>
        <form method="POST" action="admin_dashboard.php">
            <input type="hidden" id="user_id" name="user_id">
            <label for="edit-name">Name:</label><br>
            <input type="text" id="edit-name" name="name" required><br><br>

            <label for="edit-email">Email:</label><br>
            <input type="email" id="edit-email" name="email" required><br><br>

            <label for="edit-role">Role:</label><br>
            <select id="edit-role" name="role" required>
                <option value="admin">Admin</option>
                <option value="healthcare">Healthcare Personnel</option>
                <option value="patient">Patient</option>
            </select><br><br>

            <label for="edit-password">Password:</label><br>
            <input type="password" id="edit-password" name="password"><br><br>

            <button type="submit" name="update_user" style="background-color: green; color: white; padding: 10px 20px; border: none;">Update</button>
        </form>
        <button onclick="closeEditForm()" style="background-color: gray; color: white; padding: 5px 10px; border: none;">Close</button>
    </div>

    <!-- Overlay -->
    <div id="overlay" onclick="closeEditForm()"></div>

    


    <script>
        function editUser(id, name, email, role) {
            document.getElementById('user_id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-role').value = role;
            document.getElementById('edit-form').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closeEditForm() {
            document.getElementById('edit-form').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
       
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
<script>
    const ctx = document.getElementById('userChart').getContext('2d');
    const userChart = new Chart(ctx, {
        type: 'bar', // You can change this to 'pie' or 'line' as needed
        data: {
            labels: <?= $roles ?>, // Labels (Role names from PHP)
            datasets: [{
                label: 'Number of Users',
                data: <?= $counts ?>, // Count of users per role from PHP
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'], // Colors for bars
                borderColor: ['#FF6384', '#36A2EB', '#FFCE56'], // Border color for bars
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>
