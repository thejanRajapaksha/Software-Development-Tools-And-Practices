<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve advertisement ID from the form
    $advertisement_id = $_POST['advertisement_id'];

    // Handle image upload only if a new image is selected
    if ($_FILES['image']['error'] == UPLOAD_ERR_OK && $_FILES['image']['size'] > 0) {
        // Specify the directory where you want to store the uploaded image
        $uploadDirectory = "uploads/";

        // Generate a unique filename to prevent overwriting existing files
        $filename = uniqid() . "_" . basename($_FILES["image"]["name"]);
        $targetFilePath = $uploadDirectory . $filename;

        // Move the uploaded image to the target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            // Image uploaded successfully
            $image_path = $targetFilePath;
        } else {
            echo "Error moving uploaded file.";
            exit; // Exit if there's an error moving the uploaded file
        }
    } else {
        // No new image uploaded, set image path to NULL
        $image_path = null;
    }

    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "accommodationfinder";

    $connection = new mysqli($servername, $username, $password, $database);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Prepare update query
    $update_query = "UPDATE advertisements SET 
                     title = ?, 
                     location = ?, 
                     rent = ?, 
                     rooms = ?, 
                     beds = ?, 
                     image_data = ? 
                     WHERE advertisement_id = ?";

    // Prepare and bind parameters
    $stmt = $connection->prepare($update_query);
    if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }
    $stmt->bind_param("ssiiisi", $title, $location, $rent, $rooms, $beds, $image_path, $advertisement_id);

    // Set parameters
    $title = $_POST['title'];
    $location = $_POST['location'];
    $rent = $_POST['rent'];
    $rooms = $_POST['rooms'];
    $beds = $_POST['beds'];

    // Execute the update query
    if ($stmt->execute()) {
        echo "Advertisement updated successfully.";
    } else {
        echo "Error updating advertisement: " . $connection->error;
    }

    // Close the statement
    $stmt->close();

    // Close the database connection
    $connection->close();
}
?>
