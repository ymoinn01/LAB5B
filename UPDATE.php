<?php
// Database connection details
$host = "localhost";
$user = "root";
$password = "";
$dbname = "lab_5b";

// Create a connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$matric = "";
$name = "";
$role = "";

// Fetch user details for the given matric
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];
    $sql = "SELECT name, role FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $matric);
        $stmt->execute();
        $stmt->bind_result($name, $role);
        $stmt->fetch();
        $stmt->close();
    }
}

// Handle Update action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];

    // Update query
    $sql = "UPDATE users SET name = ?, role = ? WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sss", $name, $role, $matric);
        if ($stmt->execute()) {
            header("Location: user_list.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
</head>
<body>
    <h2>Update User</h2>
    <form action="" method="POST">
        <label for="matric">Matric:</label>
        <input type="text" id="matric" name="matric" value="<?php echo htmlspecialchars($matric); ?>" readonly><br><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

        <label for="role">Access Level:</label>
        <input type="text" id="role" name="role" value="<?php echo htmlspecialchars($role); ?>" required><br><br>

        <button type="submit">Update</button>
        <a href="user_list.php">Cancel</a>
    </form>
</body>
</html>