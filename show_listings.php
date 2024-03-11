<?php
session_start(); // Start session if not already started

$servername = "localhost";
$username = "root";
$password = "";
$database = "accommodationfinder";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Dashboard</title>
    <link rel="stylesheet" href="css/show_listings.css">
    <style>
        /* Your CSS styles here */
    </style>
</head>
<body>

<div class="row">
    <?php
    // Check if user is logged in
    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];

        // Retrieve listings from the database for the logged-in user
        $sql = "SELECT * FROM advertisements WHERE user_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="listing">';
                echo '<img src="data:image/jpeg;base64,'.base64_encode($row['image_data']).'" alt="'.$row['title'].'">';
                echo '<h4>'.$row['title'].'</h4>';
                echo '<p>Location: '.$row['location'].'</p>';
                echo '<p>Rent: $'.$row['rent'].'/month</p>';
                echo '<p>No. of Rooms: '.$row['rooms'].'</p>';
                echo '<p>No. of Beds: '.$row['beds'].'</p>';
                // Hidden input field to store advertisement_id
                echo '<form action="update_listing_form.php" method="post">';
                echo '<input type="hidden" name="advertisement_id" value="'.$row['advertisement_id'].'">';
                echo '<button type="submit" name="update">Update</button>';
                echo '</form>';
                echo '<form action="delete_listing.php" method="post">';
                echo '<input type="hidden" name="advertisement_id" value="'.$row['advertisement_id'].'">';
                echo '<button type="submit" name="delete">Delete</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "No listings available.";
        }

        // Close statement
        $stmt->close();
    } else {
        // Redirect to login page or handle unauthorized access
        header("Location: login.html");
        exit();
    }
    ?>
</div>

</body>
</html>

<?php
// Close the database connection
$connection->close();
?>
