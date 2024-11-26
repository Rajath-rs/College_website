<?php
include 'includes/connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $semester = $_POST['semester'];
    $department = $_POST['department'];
    $file_path = 'uploads/' . $_FILES['file']['name'];

    move_uploaded_file($_FILES['file']['tmp_name'], $file_path);

    $query = "INSERT INTO resources (title, category, semester, department, file_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiss", $title, $category, $semester, $department, $file_path);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Resource</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Add New Resource</h2>
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <form action="add_resource.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Title" required>
        <input type="text" name="category" placeholder="Category (e.g., Lecture Notes)" required>
        <input type="number" name="semester" placeholder="Semester" required>
        <input type="text" name="department" placeholder="Department" required>
        <input type="file" name="file" required>
        <button type="submit">Add Resource</button>
    </form>
</body>
</html>
