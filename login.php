<?php
include 'includes/connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare query to fetch user by email
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If user exists and password matches
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables for user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['branch'] = $user['branch'];  // Assuming 'branch' column exists in the 'users' table
            $_SESSION['semester'] = $user['semester'];  // Assuming 'semester' column exists in the 'users' table

            // Redirect to home page
            header("Location: index.php");
            exit(); // Important: Stop further script execution
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!-- HTML code for login form (remains unchanged) -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <div class="login-wrapper">
        <div class="login-container">
            <h1>Login</h1>
           
            
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <form action="login.php" method="POST">
                <div class="input-container">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-container">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Login</button>
                
            </form>
            <div class="form-links">
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </div>
            <div class="form-links">
                <p><a href="teacher_login.php">Teacher's Login </a></p>
            </div>
        </div>
    </div>
    <script>
    let isTeacherLogin = false;

    function toggleLoginType() {
        isTeacherLogin = !isTeacherLogin;

        const button = document.getElementById('toggleButton');
        const heading = document.getElementById('loginHeading');

        if (isTeacherLogin) {
            button.innerText = "Switch to User Login";
            heading.innerText = "Teacher Login";
            document.querySelector("form").action = "teacher_login.php"; // Set form action for teacher login
        } else {
            button.innerText = "Switch to Teacher Login";
            heading.innerText = "User Login";
            document.querySelector("form").action = "login.php"; // Set form action for user login
        }
    }
</script>
</body>
</html>
