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

// Initialize error message
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = trim($_POST["matric"]);
    $password = trim($_POST["password"]);

    // Validate inputs
    if (empty($matric) || empty($password)) {
        $error_message = "Both fields are required!";
    } else {
        // Query to fetch user details
        $sql = "SELECT password FROM users WHERE matric = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $matric);
            $stmt->execute();
            $stmt->store_result();

            // Check if the user exists
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($hashed_password);
                $stmt->fetch();

                // Verify the password
                if (password_verify($password, $hashed_password)) {
                    header("Location: user_list.php"); // Redirect to the user list page
                    exit;
                } else {
                    $error_message = "Invalid username or password, try <a href='login.php'>login</a> again.";
                }
            } else {
                $error_message = "Invalid username or password, try <a href='login.php'>login</a> again.";
            }
            $stmt->close();
        } else {
            $error_message = "Error: " . $conn->error;
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
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form action="" method="POST">
        <label for="matric">Matric:</label>
        <input type="text" id="matric" name="matric" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    <p>
        <a href="register.php">Register</a> here if you have not.
    </p>

    <?php
    // Display the error message if any
    if (!empty($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>
</body>
</html>