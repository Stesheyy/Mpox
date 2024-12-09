<?php
// Configure session settings BEFORE starting the session
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Set to 0 for local development
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', 3600); // 1 hour

header('Content-Type: application/json');

// Configuration constants
define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCKOUT_TIME', 300); // 5 minutes in seconds
define('SESSION_LIFETIME', 3600); // 1 hour in seconds

// Start the session before any output
session_start();


// Database configuration - UPDATE THESE WITH YOUR ACTUAL CREDENTIALS
$config = [
    'host' => 'localhost',
    'db'   => 'mpox',
    'user' => 'root',
    'pass' => ''
];

// Database connection function
function getDBConnection($config) {
    try {
        $dsn = "mysql:host={$config['host']};dbname={$config['db']};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        return new PDO($dsn, $config['user'], $config['pass'], $options);
    } catch (PDOException $e) {
        error_log("Database Connection Error: " . $e->getMessage());
        return false;
    }
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Clean and validate input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to check if user is locked out
function isUserLockedOut($pdo, $email) {
    try {
        $stmt = $pdo->prepare("SELECT failed_attempts, last_attempt FROM login_attempts WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();

        if ($result) {
            if ($result['failed_attempts'] >= MAX_LOGIN_ATTEMPTS && 
                time() - strtotime($result['last_attempt']) < LOCKOUT_TIME) {
                return true;
            }
        }
        return false;
    } catch (PDOException $e) {
        error_log("Lockout Check Error: " . $e->getMessage());
        return false;
    }
}

// Function to update login attempts
function updateLoginAttempts($pdo, $email, $success) {
    try {
        if ($success) {
            $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE email = ?");
            $stmt->execute([$email]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO login_attempts (email, failed_attempts, last_attempt) 
                                 VALUES (?, 1, NOW()) 
                                 ON DUPLICATE KEY UPDATE 
                                 failed_attempts = failed_attempts + 1,
                                 last_attempt = NOW()");
            $stmt->execute([$email]);
        }
    } catch (PDOException $e) {
        error_log("Login Attempts Update Error: " . $e->getMessage());
    }
}

// Main login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = getDBConnection($config);
        if (!$pdo) {
            throw new Exception("Database connection failed");
        }

        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];
        $role = sanitizeInput($_POST['role']); // Added role validation

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Check for lockout
        if (isUserLockedOut($pdo, $email)) {
            throw new Exception("Account is temporarily locked. Please try again later.");
        }

        // Fetch user data - NOW INCLUDING ROLE MATCH
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
        $stmt->execute([$email, $role]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            updateLoginAttempts($pdo, $email, true);

            // Regenerate session ID
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['user_id'] = $user['Id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['last_activity'] = time();

            // Redirect to appropriate dashboard based on the user role
            $response = ['success' => true];

            switch (strtolower(trim($user['role']))) {
                case 'admin':
                    $response['redirect'] = 'admin_dashboard.php';
                    break;
                case 'healthcare':
                    $response['redirect'] = 'healthcare_dashboard.php';
                    break;
                case 'patient':
                    $response['redirect'] = 'index.php';
                    break;
                default:
                    throw new Exception("Invalid user role");
            }

            echo json_encode($response);
        } else {
            updateLoginAttempts($pdo, $email, false);
            throw new Exception("Invalid email, password, or role");
        }
    } catch (Exception $e) {
        // Return error message as JSON
        error_log("Login error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}