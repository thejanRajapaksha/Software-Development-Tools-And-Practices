<?php
session_start(); // Start session if not already started

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        // Code for registering a user
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Validate inputs
        if (empty($username) || empty($email) || empty($password)) {
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

            // Check if username already exists
            $check_username_sql = "SELECT * FROM users WHERE username = ?";
            $stmt_check_username = $conn->prepare($check_username_sql);
            $stmt_check_username->bind_param("s", $username);
            $stmt_check_username->execute();
            $result_check_username = $stmt_check_username->get_result();
            if ($result_check_username->num_rows > 0) {
                echo "<script>alert('Username already exists. Please choose a different username.');</script>";
            } else {
                // Prepare and execute SQL query to insert user into database
                $sql = "INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, 'landlord')";
                $stmt_register_user = $conn->prepare($sql);
                $stmt_register_user->bind_param("sss", $username, $email, $password);

                if ($stmt_register_user->execute()) {
                    echo "<script>alert('Registration successful!');</script>";
                    // Redirect to landlord page
                    header("Location: landlord_prop.html");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }

            // Close statements
            $stmt_check_username->close();
            $stmt_register_user->close();

            // Close connection
            $conn->close();
        }
    } elseif (isset($_POST['add_listing'])) {
        // Code for adding a listing
        $title = $_POST["title"];
        $location = $_POST["location"];
        $rent = $_POST["rent"];
        $rooms = $_POST["rooms"];
        $beds = $_POST["beds"];
        $user_id = $_SESSION["user_id"]; // Retrieve user_id from session

        // Validate inputs
        if (empty($title) || empty($location) || empty($rent) || empty($rooms) || empty($beds)) {
            echo "<script>alert('All fields are required.');</script>";
        } else {
            // Check if image file is uploaded
            if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
                // Check for upload errors
                if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                    echo "<script>alert('Error uploading image. Please try again.');</script>";
                    exit();
                }

                // Read image file
                $image_data = file_get_contents($_FILES['image']['tmp_name']); // Directly read image file contents

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

                $user_id = $_SESSION["user_id"]; // Retrieve user_id from session

                // Prepare and execute SQL query to insert listing into database
                $sql = "INSERT INTO advertisements (title, location, rent, rooms, beds, image_data, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssiiii", $title, $location, $rent, $rooms, $beds, $image_data, $user_id); // Use "b" for BLOB data type


                if ($stmt->execute()) {
                    echo "<script>alert('Listing added successfully!');</script>";
                    // Redirect back to landlord page
                    header("Location: landlord_prop.html");
                    exit(); // Stop further execution
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                // Close statement
                $stmt->close();

                // Close connection
                $conn->close();
            } else {
                echo "<script>alert('Please select an image.');</script>";
            }
        }
    }
}

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.html");
    exit();
}
?>
