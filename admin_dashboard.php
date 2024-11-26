<?php
include 'includes/connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch Resources
$query = "SELECT * FROM resources ORDER BY id DESC";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <a href="add_resource.php">Add New Resource</a>
    <table>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Semester</th>
            <th>Department</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td><?php echo $row['semester']; ?></td>
            <td><?php echo $row['department']; ?></td>
            <td>
                <a href="edit_resource.php?id=<?php echo $row['id']; ?>">Edit</a>
                <a href="delete_resource.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <a href="admin_logout.php">Logout</a>
</body>
</html>
