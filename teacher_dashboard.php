<?php
include 'includes/connect.php';
session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $branch = $_POST['branch'];
    $semester = $_POST['semester'];
    $category = $_POST['category'];
    $file_path = 'uploads/' . basename($_FILES['file']['name']);
    move_uploaded_file($_FILES['file']['tmp_name'], $file_path);

    $query = "INSERT INTO resources (title, branch, semester, category, file_path, uploaded_by) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $title, $branch, $semester, $category, $file_path, $teacher_id);

    if ($stmt->execute()) {
        echo "Resource uploaded successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <link rel="stylesheet" href="teacher.css">
</head>
<body>
    

    <h1>Welcome, Teacher!</h1>
    
    <a href="index.php">Home</a>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title:</label>
            <select id="title" name="title" required>
                <option value="Assignment">Assignment</option>
                <option value="Lecture Notes">Lecture Notes</option>
                <option value="Project">Project</option>
                <option value="Exam Paper">Exam Paper</option>
                <option value="Other">Other</option>
            </select>
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

        <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required placeholder="Enter the resource category">
        </div>

        <div class="form-group">
            <label for="file">Upload File:</label>
            <input type="file" id="file" name="file" required>
        </div>

        <button type="submit">Upload Resource</button>
    </form>
</body>
</html>
