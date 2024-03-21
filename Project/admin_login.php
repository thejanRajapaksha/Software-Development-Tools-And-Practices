<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form action="admin_login.php" method="POST">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="login">Login</button>
    </form>

    <?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "accommodationfinder";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = $_POST["password"];

    // Retrieve admin data from the database
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        // Check if password matches
        if ($password === $admin['password']) {
            echo "Login successful! Redirecting to admin dashboard...";
            // Redirect to admin dashboard page
            header("Location: admin_dashboard.php");
            exit();
        } else {
            // Display alert for invalid password
            echo '<script>alert("Invalid password.");</script>';
        }
    } else {
        // Display alert for invalid username
        echo '<script>alert("Invalid username.");</script>';
    }
}

// Close the database connection
$conn->close();
?>

</body>
</html>
