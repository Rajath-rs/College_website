<?php
include 'includes/connect.php';
session_start(); // Start session at the top

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check for teacher login credentials
    $query = "SELECT * FROM teachers WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $teacher = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $teacher['password'])) {
            $_SESSION['teacher_id'] = $teacher['id'];  // Set session variable
            $_SESSION['teacher_email'] = $teacher['email'];  // Optionally, store email or other info
            // Redirect to teacher dashboard
            header("Location: teacher_dashboard.php");
            exit(); // Ensure no further code is executed after redirect
        } else {
            echo "Invalid credentials!";  // Invalid password
        }
    } else {
        echo "Invalid credentials!";  // No user found
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <link rel="stylesheet" href="tlogin.css">
</head>
<body>
    <h1>Teacher Login</h1>
    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
        <div class="form-links">
                <p>Don't have an account? <a href="teacher_register.php">Register</a></p>
            </div>
    </form>
</body>
</html>
