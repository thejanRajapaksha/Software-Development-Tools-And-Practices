<?php
session_start(); // Start session if not already started

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category'])) {
    // Retrieve form data
    $category = $_POST["category"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate inputs
    if (empty($category) || empty($username) || empty($password)) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        // Connect to your database (replace with your own database credentials)
        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "accommodationfinder";

        // Create connection
        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute SQL query to fetch user from database
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password' AND user_type='$category'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Store username in session
            $_SESSION["username"] = $username;

            // Fetch user_id
            $row = $result->fetch_assoc();
            $user_id = $row['user_id'];

            // Store user_id in session
            $_SESSION["user_id"] = $user_id;

            // Redirect based on user category
            switch ($category) {
                case 'student':
                    header("Location: index.php");
                    exit();
                    break;
                case 'warden':
                    header("Location: warden.html");
                    exit();
                    break;
                case 'landlord':
                    // Redirect to landlord login functionality
                    // Retrieve form data
                    $username = $_POST["username"];
                    $password = $_POST["password"];

                    // Prepare and execute SQL query to fetch user from database
                    $sql = "SELECT user_id FROM users WHERE username=? AND password=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $username, $password);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 1) {
                        // Fetch user_id
                        $row = $result->fetch_assoc();
                        $user_id = $row['user_id'];

                        // Store username and user_id in session
                        $_SESSION["username"] = $username;
                        $_SESSION["user_id"] = $user_id;

                        // Redirect to landlord property page
                        header("Location: landlord_prop.html");
                        exit();
                    } else {
                        echo "<script>alert('Invalid username or password.');</script>";
                    }

                    // Close statement
                    $stmt->close();
                    break;
                default:
                    // Handle error or invalid category
                    break;
            }
        } else {
            echo "<script>alert('Invalid username, password, or user category.');</script>";
        }

        // Close connection
        $conn->close();
    }
}
?>
