<?php
session_start();

// Authentication check
if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Check for request ID
if(!isset($_GET['id'])) {
    header("Location: " . ($_SESSION['user_role'] == 'admin' ? "admin_dashboard.php" : "user_dashboard.php"));
    exit;
}

require_once "../app/controllers/RequestController.php";
$requestController = new RequestController();

$request = $requestController->getRequest($_GET['id']);

// Ensure user can only edit their own requests unless admin
if (!$request || ($_SESSION['user_role'] != 'admin' && $request->user_id != $_SESSION['user_id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

// Process form submission
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $requestData = array(
        "id" => $request->id,
        "title" => $_POST['title'],
        "description" => $_POST['description'],
        "category" => $_POST['category'],
        "status" => isset($_POST['status']) ? $_POST['status'] : $request->status
    );
    
    if($requestController->updateRequest($requestData)) {
        header("Location: " . ($_SESSION['user_role'] == 'admin' ? "admin_dashboard.php" : "user_dashboard.php"));
        exit;
    } else {
        $edit_error = "Failed to update request";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SSRS - Edit Request</title>
    <style>
        /* Global Styling */
body {
    font-family: "Poppins", sans-serif;
    background-color: #f8f8f8;
    color: #333;
    margin: 0;
    padding: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    flex-direction: column;
}

/* Centered Content Wrapper */
.container {
    width: 50%;
    max-width: 650px;
    background-color: #ffffff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.1);
    text-align: left;
    border: 1px solid #ddd;
    position: relative;
}

/* Positioning Header and Title */
h2 {
    font-size: 26px;
    margin-bottom: 10px;
    text-align: center;
    color: #444;
}

h3 {
    font-size: 22px;
    margin-bottom: 20px;
    text-align: center;
    color: #666;
}

/* Back to Dashboard Link */
.dashboard-link {
    position: absolute;
    top: 10px;
    right: 20px; /* Change to 'left: 20px' for left alignment */
    font-size: 16px;
    color: #0077cc;
    text-decoration: none;
}

.dashboard-link:hover {
    text-decoration: underline;
}

/* Form Styling */
form div {
    margin-bottom: 15px;
}

input[type="text"], textarea, select {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
    background-color: #fafafa;
    box-shadow: inset 0px 2px 4px rgba(0, 0, 0, 0.05);
}

/* Button Styling */
button {
    background-color: blue;
    color: #fff;
    font-size: 18px;
    padding: 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: block;
    width: 100%;
    transition: all 0.3s ease-in-out;
}

button:hover {
    background-color:rgb(206, 96, 0);
    transform: scale(1.03);
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 80%;
    }

    h2 {
        font-size: 24px;
    }
}

 

    </style>
</head>
<body>
    <h2>Smart Service Request System</h2>
    <h3>Edit Service Request</h3>
    
    <?php if(isset($edit_error)): ?>
        <p style="color: red;"><?php echo $edit_error; ?></p>
    <?php endif; ?>
    
    <form method="post" action="">
        <div>
            <label>Title:</label>
            <input type="text" name="title" value="<?php echo $request->title; ?>" required>
        </div>
        <div>
            <label>Description:</label>
            <textarea name="description" required><?php echo $request->description; ?></textarea>
        </div>
        <div>
            <label>Category:</label>
            <select name="category" required>
                <option value="IT Support" <?php echo $request->category == 'IT Support' ? 'selected' : ''; ?>>IT Support</option>
                <option value="Maintenance" <?php echo $request->category == 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                <option value="Administrative" <?php echo $request->category == 'Administrative' ? 'selected' : ''; ?>>Administrative</option>
                <option value="Other" <?php echo $request->category == 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>
        
        <?php if($_SESSION['user_role'] == 'admin'): ?>
        <div>
            <label>Status:</label>
            <select name="status">
                <option value="pending" <?php echo $request->status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="in-progress" <?php echo $request->status == 'in-progress' ? 'selected' : ''; ?>>In Progress</option>
                <option value="resolved" <?php echo $request->status == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
            </select>
        </div>
        <?php endif; ?>
        
        <div>
            <button type="submit">Update Request</button>
        </div>
    </form>
    
    <p><a href="<?php echo $_SESSION['user_role'] == 'admin' ? 'admin_dashboard.php' : 'user_dashboard.php'; ?>">Back to Dashboard</a></p>
</body>
</html>