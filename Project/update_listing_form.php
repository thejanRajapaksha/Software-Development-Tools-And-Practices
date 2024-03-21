<?php
// Initialize variables to store advertisement details
$title = $location = $rent = $rooms = $beds = $image_data = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve advertisement ID from the form
    $advertisement_id = $_POST['advertisement_id'];

    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "accommodationfinder";

    $connection = new mysqli($servername, $username, $password, $database);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Prepare select query
    $select_query = "SELECT title, location, rent, rooms, beds, image_data 
                     FROM advertisements 
                     WHERE advertisement_id = ?";

    // Prepare and bind parameters
    $stmt = $connection->prepare($select_query);
    $stmt->bind_param("i", $advertisement_id);

    // Execute the query
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($title, $location, $rent, $rooms, $beds, $image_data);

    // Fetch the data
    $stmt->fetch();

    // Close the statement
    $stmt->close();

    // Close the database connection
    $connection->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Advertisement</title>
</head>
<body>
    <h2>Update Advertisement</h2>
    <form action="update_listing.php" method="POST" enctype="multipart/form-data">
        <!-- Display form fields for updating advertisement details -->
        <div class="input-group">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" value="<?php echo $title; ?>" required><br>
        </div>
        <div class="input-group">
            <label for="location">Location:</label><br>
            <input type="text" id="location" name="location" value="<?php echo $location; ?>" required><br>
        </div>
        <div class="input-group">
            <label for="rent">Rent:</label><br>
            <input type="number" id="rent" name="rent" value="<?php echo $rent; ?>" required><br>
        </div>
        <div class="input-group">
            <label for="rooms">Number of Rooms:</label><br>
            <input type="number" id="rooms" name="rooms" value="<?php echo $rooms; ?>" required><br>
        </div>
        <div class="input-group">
            <label for="beds">Number of Beds</label><br>
            <input type="number" id="beds" name="beds" value="<?php echo $beds; ?>" required><br>
        </div>
        <label for="image">Image:</label><br>
        <input type="file" id="image" name="image" accept="image/*"><br>
        <!-- Optionally, you can display the existing image -->
        <?php if ($image_data): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($image_data); ?>" alt="Existing Image"><br>
        <?php endif; ?>
        <br>
        <!-- Add a hidden input field to send the ID of the advertisement being updated -->
        <input type="hidden" name="advertisement_id" value="<?php echo $advertisement_id; ?>">
        <button type="submit" name="update_listing">Update Listing</button>
    </form>
</body>
</html>
