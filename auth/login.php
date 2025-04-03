<?php
session_start(); // Ensure session is started

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../public/index.php");
    exit;
}

// Corrected require_once path using __DIR__
require_once dirname(__FILE__) . "/../app/controllers/UserController.php";

$userController = new UserController();

$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($email && $password) {
        $user = $userController->login($email, $password);

        if ($user) {
            // Debugging: Check user data (optional, remove or comment out after debugging)
            // var_dump($user);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect based on role
            header("Location: " . ($user['role'] == 'admin' ? '../public/admin_dashboard.php' : '../public/user_dashboard.php'));
            exit;
        } else {
            $login_error = "Invalid email or password.";
        }
    } else {
        $login_error = "Please fill in both email and password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SSRS - Login</title>
    <style>
        /* General Styling */
        body {
            background-color: antiquewhite;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Form Container */
        .container {
            width: 350px;
            padding: 25px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Header */
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Input Fields */
        input[type="email"], 
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: rgb(16, 227, 220);
            outline: none;
            box-shadow: 0 0 6px rgba(0, 140, 186, 0.3);
        }

        /* Button */
        button {
            width: 100%;
            padding: 12px;
            background-color: rgb(16, 227, 220);
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: rgb(16, 227, 220);
        }

        /* Error Message */
        .message.error {
            color: #d8000c;
            background-color: #ffdddd;
            padding: 10px;
            border-radius: 6px;
            border-left: 4px solid #ff4444;
            text-align: center;
            margin-bottom: 15px;
        }

        /* Links */
        .links {
            text-align: center;
            margin-top: 15px;
        }

        .links a {
            color: rgb(16, 227, 220);
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Smart Service Request System</h2>
        <h3>Login</h3>

        <?php if (isset($login_error)): ?>
            <div class="message error"><?php echo htmlspecialchars($login_error); ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>

        <div class="links">
            <p>Don't have an account? <a href="../public/register.php">Register here</a></p>
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </div>
</body>
</html>