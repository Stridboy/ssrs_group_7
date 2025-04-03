<?php
session_start();
if (isset($_SESSION['username'])) {
  if ($_SESSION['user_role'] != 'admin') {
    header('Location: user.php');
    exit;
  }
} else {
  header('Location: login.html');
  exit;
}
include '../../config/database.php';
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM requests WHERE status = 'pending'";
$result = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | Request Management</title>
  <style>
    :root {
      --primary: #4361ee;
      --secondary: #3f37c9;
      --success: #4cc9f0;
      --danger: #f72585;
      --warning: #f8961e;
      --light: #f8f9fa;
      --dark: #212529;
      --gray: #6c757d;
      --white: #ffffff;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      line-height: 1.6;
      background-color: #f8fafc;
      color: var(--dark);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    
    .container {
      width: 100%;
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1.5rem;
    }
    
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2.5rem;
      width: 100%;
    }
    
    .page-title {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--primary);
    }
    
    .logout-btn {
      background-color: var(--danger);
      color: var(--white);
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 0.375rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .logout-btn:hover {
      background-color: #d1144a;
      transform: translateY(-1px);
    }
    
    .section-title {
      font-size: 1.4rem;
      font-weight: 600;
      color: var(--secondary);
      margin-bottom: 1.5rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid #e9ecef;
      text-align: center;
    }
    
    .requests-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }
    
    .request-card {
      background: var(--white);
      border-radius: 0.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      padding: 1.5rem;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      border-left: 4px solid var(--primary);
      width: 100%;
      max-width: 800px;
      margin-bottom: 1.5rem;
    }
    
    .request-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .request-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1rem;
    }
    
    .request-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 0.5rem;
    }
    
    .request-status {
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      background-color: var(--warning);
      color: var(--dark);
    }
    
    .request-description {
      color: var(--gray);
      margin-bottom: 1.5rem;
    }
    
    .response-section {
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid #e9ecef;
    }
    
    .response-title {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 0.75rem;
      color: var(--secondary);
    }
    
    .response-form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    
    .response-input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ced4da;
      border-radius: 0.375rem;
      font-family: inherit;
      transition: border-color 0.2s ease;
    }
    
    .response-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
    }
    
    .btn {
      padding: 0.6rem 1.2rem;
      border-radius: 0.375rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s ease;
      border: none;
      font-size: 0.875rem;
    }
    
    .btn-primary {
      background-color: var(--primary);
      color: var(--white);
    }
    
    .btn-primary:hover {
      background-color: var(--secondary);
    }
    
    .btn-secondary {
      background-color: var(--success);
      color: var(--white);
    }
    
    .btn-secondary:hover {
      background-color: #3ab7d8;
    }
    
    .action-buttons {
      display: flex;
      gap: 0.75rem;
      margin-top: 1.5rem;
      justify-content: center;
    }
    
    .empty-state {
      text-align: center;
      padding: 3rem;
      color: var(--gray);
      width: 100%;
      max-width: 800px;
      background: var(--white);
      border-radius: 0.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .centered-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }
  </style>
</head>

<body>
  <div class="container">
    <header>
      <h1 class="page-title">Admin Dashboard</h1>
      <a href="../../auth/logout.php" class="logout-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
          <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
        </svg>
        Logout
      </a>
    </header>

    <main class="centered-content">
      <h2 class="section-title">Pending Requests</h2>
      
      <div class="requests-container">
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="request-card">
              <div class="request-header">
                <h3 class="request-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                <span class="request-status"><?php echo htmlspecialchars($row['status']); ?></span>
              </div>
              
              <p class="request-description"><?php echo htmlspecialchars($row['description']); ?></p>
              
              <div class="response-section">
                <h4 class="response-title">Response</h4>
                <?php
                $id = $row['id'];
                $sql1 = "SELECT * FROM responses WHERE request_id = '$id'";
                $result1 = mysqli_query($db, $sql1);
                if (mysqli_num_rows($result1) == 0): ?>
                  <form action="../models/response.php?id=<?php echo $row['id']; ?>" method="POST" class="response-form">
                    <textarea name="response" class="response-input" rows="3" required placeholder="Type your response here..."></textarea>
                    <button type="submit" class="btn btn-primary">Submit Response</button>
                  </form>
                <?php else:
                  $row1 = mysqli_fetch_assoc($result1); ?>
                  <p><?php echo htmlspecialchars($row1['response_message']); ?></p>
                <?php endif; ?>
              </div>
              
              <div class="action-buttons">
                <a href="../models/solved.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Mark as Solved</a>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="empty-state">
            <h3>No pending requests found </h3>
            <p>All requests have been processed</p>
          </div>
        <?php endif; ?>
      </div>
    </main>
  </div>
</body>
</html>