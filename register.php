<?php
include 'includes/connect.php';
session_start();

// Check if the user is logged in
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $branch = $_POST['branch'];
    $semester = $_POST['semester'];

    // Debugging: Print the value of branch to check if it is properly received
    // You can remove this after debugging
    echo "Branch selected: " . $branch;

    // Prepare the SQL query to insert user data
    $query = "INSERT INTO users (name, email, password, branch, semester) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    // Check if prepare was successful
    if ($stmt === false) {
        die('Error preparing the query: ' . $conn->error);
    }

    // Bind parameters - Ensure data types are correct
    // s = string, i = integer
    $stmt->bind_param("ssssi", $name, $email, $password, $branch, $semester);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to the login page after successful registration
        header("Location: login.php");
        exit();
    } else {
        // If there is an error, display it
        $error = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required placeholder="Enter your name">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required placeholder="Enter your password">
            </div>

            <div class="form-group">
                <label for="branch">Branch:</label>
                <select id="branch" name="branch" required>
                    <option value="CSE">CSE (Computer Science and Engineering)</option>
                    <option value="MECH">MECH (Mechanical Engineering)</option>
                    <option value="CIVIL">CIVIL (Civil Engineering)</option>
                    <option value="ISE">ISE (Information Science and Engineering)</option>
                    <option value="AIML">AIML (Artificial Intelligence and Machine Learning)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="semester">Semester:</label>
                <select id="semester" name="semester" required>
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                    <option value="3">Semester 3</option>
                    <option value="4">Semester 4</option>
                    <option value="5">Semester 5</option>
                    <option value="6">Semester 6</option>
                    <option value="7">Semester 7</option>
                    <option value="8">Semester 8</option>
                </select>
            </div>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
