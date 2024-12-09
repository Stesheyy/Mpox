<?php
// Database connection setup
$host = 'localhost';
$db = 'mpox';
$user = 'root';
$password = '';
$port ='3306';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $plainPassword = $_POST['password']; // Save plain password for validation
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
    $role = htmlspecialchars($_POST['role']);

    try {
        // Check if admin already exists
        if ($role === 'admin') {
            $checkAdminStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
            $checkAdminStmt->execute();
            if ($checkAdminStmt->fetchColumn() > 0) {
                echo "<script>alert('An admin account already exists. Please contact the existing admin.'); window.history.back();</script>";
                exit;
            }
        }

        // Check if email already exists
        $checkEmailStmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $checkEmailStmt->execute([$email]);
        if ($checkEmailStmt->rowCount() > 0) {
            echo "<script>alert('Email already registered. Please log in.'); window.history.back();</script>";
            exit;
        }

        // Insert user into database
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $hashedPassword, $role])) {
            echo "<script>alert('Registration successful! You have been registered as $role.'); window.location.href = '../login.html';</script>";
            exit;
        } else {
            echo "<script>alert('Failed to register. Please try again.'); window.history.back();</script>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<script>alert('An error occurred: " . $e->getMessage() . "'); window.history.back();</script>";
        exit;
    }
}
?>
