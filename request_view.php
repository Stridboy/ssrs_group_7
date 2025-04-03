<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the RequestController class with absolute path
require_once $_SERVER['DOCUMENT_ROOT'] . '/ssrs/app/controllers/requestcontroller.php';

// Check if request ID is provided
if (!isset($_GET['id'])) {
    header("Location: user_dashboard.php");
    exit();
}

$request_id = $_GET['id'];

// Create an instance of the RequestController
$requestController = new RequestController();

// Get request details
$request = $requestController->getRequest($request_id);

// Check if the request exists
if (!$request) {
    header("Location: user_dashboard.php");
    exit();
}

// Get responses for this request
$responses = $requestController->getResponses($request_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details</title>
    <!-- Include your CSS files here -->
     <style>
/* Global Styling */
body {
    font-family: "Arial", sans-serif;
    background-color: #f9f9f9;
    color: #333;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}

/* Main Wrapper */
.container {
    width: 60%;
    max-width: 600px;
    background: #ffffff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.1);
    text-align: left;
    border: 2px solid #bbb;
}

/* Request Details Section */
.request-details {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 8px;
    border: 2px solid #888;
    margin-bottom: 20px;
}

/* Request Information */
.request-info {
    padding: 15px;
    border-radius: 8px;
    border: 2px solid #ccc;
    background: #f9f9f9;
}

/* Properly Ordered Elements */
.request-details h2 {
    font-size: 22px;
    color: #444;
    margin-bottom: 15px;
    text-align: center;
    border-bottom: 2px solid #ddd;
    padding-bottom: 8px;
}

/* Responses Section (Now Above "No Responses Yet") */
.responses {
    padding: 15px;
    border-radius: 8px;
    border: 2px solid #777;
    background: #ececec;
    margin-bottom: 20px;
}

/* Individual Response */
.response {
    background: #ffffff;
    padding: 12px;
    border-radius: 6px;
    border-left: 4px solid #444;
}

/* No Responses Yet Message */
.no-responses {
    text-align: center;
    font-size: 16px;
    padding: 10px;
    color: #777;
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
    <!-- Your HTML code here -->
    <h1>Request Details</h1>
    
    <!-- Display request information -->
    <div class="request-details">
        <h2><?php echo htmlspecialchars($request->title); ?></h2>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($request->category); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($request->status); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($request->description); ?></p>
    </div>
    
    <!-- Display responses -->
    <h3>Responses</h3>
    <div class="responses">
        <?php
        $hasResponses = false;
        if ($responses && $responses->rowCount() > 0) {
            $hasResponses = true;
            while ($row = $responses->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="response">';
                echo '<p><strong>Admin:</strong> ' . htmlspecialchars($row['admin_id']) . '</p>';
                echo '<p>' . htmlspecialchars($row['response_message']) . '</p>';
                echo '</div>';
            }
        }
        
        if (!$hasResponses) {
            echo '<p>No responses yet.</p>';
        }
        ?>
    </div>
    
    <!-- Add more HTML as needed -->
</body>
</html>