<?php
include '../../config/database.php';
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM requests WHERE id = '$id'";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_assoc($result);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $tile = $_POST['title'];
  $category = $_POST['category'];
  $description = $_POST['description'];

  $sql = "UPDATE requests SET title='$tile', description='$description', category='$category' WHERE id = '$id'";
  if (mysqli_query($db, $sql)) {
    header('Location: ../views/user.php');
  }
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../public/assets/css/styles.css">
  <title>Update Request</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .formContainer {
      background: #ffffff;
      padding: 20px 30px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .formContainer form {
      display: flex;
      flex-direction: column;
    }

    .formContainer label {
      margin-bottom: 8px;
      font-weight: bold;
      color: #333;
    }

    .formContainer input,
    .formContainer select,
    .formContainer textarea {
      margin-bottom: 15px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
      width: 100%;
    }

    .formContainer textarea {
      resize: none;
      height: 100px;
    }

    .formContainer button {
      background-color: #007bff;
      color: #fff;
      padding: 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }

    .formContainer button:hover {
      background-color: #0056b3;
    }
  </style>
</head>

<body>
  <div class="formContainer">
    <form action="" method="POST">
      <label>Title</label>
      <input value="<?php echo "{$row['title']}" ?>" type="text" placeholder="Enter the title" name="title" required>
      <label>Category</label>
      <select name="category" required>
        <option value=""></option>
        <option value="admin tasks" <?php echo $row['category'] == 'admin tasks' ? 'selected' : ''; ?>>Admin Tasks</option>
        <option value="it support" <?php echo $row['category'] == 'it support' ? 'selected' : ''; ?>>IT Support</option>
        <option value="maintenance" <?php echo $row['category'] == 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
      </select>
      <label>Description</label>
      <textarea name="description" placeholder="Describe the requested Service" required><?php echo "{$row['description']}" ?></textarea>
      <button type="submit">Update</button>
    </form>
  </div>
</body>

</html>