<?php
// Start or resume session
session_start();

// Establish a connection to the database
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

// Check if user is logged in
$student_id = null;
if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];
}

// Reservation logic
$reservationMessage = ""; // Initialize reservation message
if (isset($_POST['reserve'])) {
    if (!$student_id) {
        $reservationMessage = "Please login to reserve a property.";
    } else {
        $advertisement_id = $_POST['advertisement_id'];
        
        // Check if the user has already reserved this property
        $check_reservation_sql = "SELECT * FROM reservations WHERE student_id = '$student_id' AND advertisement_id = '$advertisement_id'";
        $check_reservation_result = $connection->query($check_reservation_sql);
        
        if ($check_reservation_result->num_rows > 0) {
            $reservationMessage = "You have already reserved this property. Please wait for the landlord's response.";
        } else {
            // Insert reservation into the database
            $reserve_sql = "INSERT INTO reservations (student_id, advertisement_id) VALUES ('$student_id', '$advertisement_id')";
            
            if ($connection->query($reserve_sql) === TRUE) {
                $reservationMessage = "Reservation successful!";
                // Execute JavaScript to display the message box
                echo '<script>alert("' . $reservationMessage . '");</script>';
            } else {
                $reservationMessage = "Error: " . $reserve_sql . "<br>" . $connection->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accommodation Website</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>NSBM Green University Accommodation Finder</h1>
    </header>

    <!-- Navbar Start -->
    <center>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if ($student_id) : ?>
                    <!-- If user is logged in, you can optionally include a "Logout" option -->
                     <li><a href="notification.php">Notifications</a></li> 
                <?php else : ?>
                    <li><a href="login.html">Login</a></li>
                <?php endif; ?>
                <li><a href="landlord_prop.html">Landlord</a></li>
                <li><a href="warden.html">Warden</a></li>
            </ul>
        </nav>
    </center>
    <!-- Navbar End -->
    
    <main>
        <h2>Welcome to NSBM Green University Accommodation Finder</h2>
        <p>Find the perfect accommodation near NSBM Green University.</p>
        <h3>Latest Listings</h3>
        <div class="row">
            <?php
            // Assuming you have a database connection established
            $sql = "SELECT * FROM advertisements";
            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="listing">';
                    $image_src = $row['image_data']; // Assuming 'image_data' contains the file name
                    echo '<img src="' . $image_src . '" alt="' . $row['title'] . '">';
                    echo '<h4>'.$row['title'].'</h4>';
                    echo '<p>Location: '.$row['location'].'</p>';
                    echo '<p>Rent: $'.$row['rent'].'/month</p>';
                    echo '<p>No. of Rooms: '.$row['rooms'].'</p>';
                    echo '<p>No. of Beds: '.$row['beds'].'</p>';
                    if ($student_id) {
                        echo '<form method="post" action="">';
                        echo '<input type="hidden" name="student_id" value="'.$student_id.'">';
                        echo '<input type="hidden" name="advertisement_id" value="'.$row['advertisement_id'].'">';
                        echo '<button type="submit" name="reserve">Reserve Property</button>';
                        echo '</form>';
                    } else {
                        echo '<br><p><a href="login.html">Login</a> to reserve this property.</p>';
                    }
                    echo '</div>';
                }
            } else {
                echo "No listings available.";
            }
            ?>
        </div>
    </main>
    
    <footer>
        <p>&copy; 2024 NSBM Green University</p>
    </footer>
</body>
</html>
<?php
// Close the database connection
$connection->close();
?>
