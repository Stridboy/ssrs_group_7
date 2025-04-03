<?php
session_start();

// Authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../app/controllers/UserController.php";
$userController = new UserController();

$userId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$userId) {
    header("Location: admin_dashboard.php");
    exit;
}

// Get user data
$user = $userController->getUserById($userId);

if (!$user) {
    header("Location: admin_dashboard.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userData = [
        'id' => $userId,
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'password' => $_POST['password'] ?? null, // Optional password update
    ];

    if ($userController->updateUser($userData)) {
        $_SESSION['success'] = "User updated successfully.";
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Failed to update user.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SSRS - Edit User</title>
    
    <style>
        /* Global Styling */
body {
    font-family: "Arial", sans-serif;
    background-color: #f9f9f9;
    color: #333;
    margin: 0;
    padding: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* Centered Container */
.container {
    width: 50%;
    max-width: 600px;
    background: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    text-align: left;
    border: 2px solid #ccc;
}

/* Header Styling */
h2 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 10px;
    color: #444;
    border-bottom: 2px solid #ddd;
    padding-bottom: 8px;
}

/* Error Message */
.message.error {
    background-color: #ffdddd;
    padding: 10px;
    border-radius: 6px;
    border-left: 4px solid #ff4444;
    margin-bottom: 15px;
    font-weight: bold;
    color: #d8000c;
}

/* Form Layout */
.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #555;
}

/* Input Fields */
input[type="text"], input[type="email"], input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
    background-color: #fafafa;
}

/* Buttons */
button {
    background-color: #ff7b00;
    color: #fff;
    font-size: 18px;
    padding: 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
}

button:hover {
    background-color: #e66a00;
}

/* Back to Dashboard Link */
p a {
    text-decoration: none;
    color: #0077cc;
    font-size: 16px;
    display: block;
    text-align: center;
    margin-top: 10px;
}

p a:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 80%;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>

        <?php if (isset($error)): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">New Password (optional):</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-group">
                <button type="submit">Update User</button>
            </div>
        </form>

        <p><a href="admin_dashboard.php">← Back to Dashboard</a></p>
    </div>
</body>
</html>