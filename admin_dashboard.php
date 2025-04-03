<?php
session_start();

// Authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../app/controllers/RequestController.php";
require_once "../app/controllers/UserController.php";

$requestController = new RequestController();
$userController = new UserController();

$requests = $requestController->getAllRequests(); // Now returns PDOStatement
$users = $userController->getAllUsers(); // Get all users from the database
?>
<!DOCTYPE html>
<html>
<head>
    <title>SSRS - Admin Dashboard</title>
    <style>
/* General Styling */
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f5f5;
    color: #333;
    margin: 0;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Main Container */
.container {
    width: 95%;
    max-width: 1200px;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 18px rgba(0, 0, 0, 0.15);
    animation: fadeIn 0.6s ease-in-out;
}

/* Headings */
h2, h3, h4 {
    text-align: center;
    color:green;
    font-weight: 600;
}

/* User Welcome Section */
div p {
    text-align: center;
    font-size: 16px;
    font-weight: 500;
}

/* Tables */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}

th, td {
    padding: 14px;
    text-align: left;
    border-bottom: 2px solid #ddd;
}

th {
    background:rgb(66, 219, 236); ;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
}

tr:hover {
    background: rgba(52, 152, 219, 0.1);
    transition: background 0.3s ease;
}

/* Table Actions */
td a {
    color:green;
    font-weight: 500;
    text-decoration: none;
    padding: 6px 10px;
    display: inline-block;
    border-radius: 5px;
    transition: 0.3s;
}

td a:hover {
    color: white;
    background: #2980b9;
}

/* Buttons */
button {
    background: #3498db;
    color: white;
    padding: 12px 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    transition: transform 0.2s ease, background 0.3s ease;
}

button:hover {
    background:rgb(66, 219, 236);
    transform: scale(1.05);
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 100%;
        padding: 20px;
    }
    
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    
    button {
        width: 100%;
    }
}

/* Fade-In Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}



    </style>
</head>
<body>
    <h2>Smart Service Request System</h2>
    <h3>Admin Dashboard</h3>
    
    <div>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Admin) | 
           <a href="../auth/logout.php">Logout</a>
        </p>
    </div>
    
    <div>
        <h4>All Service Requests</h4>
        
        <?php if ($requests->rowCount() > 0): ?>
            <table>
                <tr>
                    <th>User</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $requests->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <a href="request_view.php?id=<?php echo $row['id']; ?>">View</a> | 
                            <a href="request_edit.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                            <a href="request_delete.php?id=<?php echo $row['id']; ?>" 
                               onclick="return confirm('Are you sure you want to delete this request?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No requests found.</p>
        <?php endif; ?>
    </div>
    
    <div>
        <h4>All Users</h4>
        
        <?php if ($users->rowCount() > 0): ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $users->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <a href="change_password.php?id=<?php echo $row['id']; ?>">Change Password</a> | 
                            <a href="user_edit.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                            <a href="user_delete.php?id=<?php echo $row['id']; ?>" 
                               onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
