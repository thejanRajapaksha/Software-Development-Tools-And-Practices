<?php
session_start(); // Start session if not already started

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Code for registering a user
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $telephone = $_POST["telephone"];

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($telephone)) {
        $message = "All fields are required.";
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

        // Check if username already exists
        $check_username_sql = "SELECT * FROM landlord WHERE username = ?";
        $stmt_check_username = $conn->prepare($check_username_sql);
        $stmt_check_username->bind_param("s", $username);
        $stmt_check_username->execute();
        $result_check_username = $stmt_check_username->get_result();
        if ($result_check_username->num_rows > 0) {
            echo "<script>alert('Username already exists. Please choose a different username.');</script>";
        } else {
            // Prepare and execute SQL query to insert user into database
            $sql = "INSERT INTO landlord (username, email, password, telephone) VALUES (?, ?, ?, ?)";
            $stmt_register_user = $conn->prepare($sql);
            $stmt_register_user->bind_param("ssss", $username, $email, $password, $telephone); // Corrected parameter count

            // Check if statement preparation was successful
            if ($stmt_register_user) {
                if ($stmt_register_user->execute()) {
                    echo "<script>alert('Registration Successfull.');</script>";
                    // Redirect to landlord page
                    header("Location: landlord_prop.html");
                    exit();
                } else {
                    $message = "Error: " . $sql . "<br>" . $conn->error;
                }
                // Close statement
                $stmt_register_user->close();
            } else {
                $message = "Error preparing statement.";
            }

        }

        // Close statements
        $stmt_check_username->close();

        // Close connection
        $conn->close();
    }
}
?>
