<?php
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
                <li><a href="login.html">Login</a></li>
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
                    echo '<img src="data:image/jpeg;base64,'.base64_encode($row['image_data']).'" alt="'.$row['title'].'">';
                    echo '<h4>'.$row['title'].'</h4>';
                    echo '<p>Location: '.$row['location'].'</p>';
                    echo '<p>Rent: $'.$row['rent'].'/month</p>';
                    echo '<p>No. of Rooms: '.$row['rooms'].'</p>';
                    echo '<p>No. of Beds: '.$row['beds'].'</p>';
                    echo '<button>View Details</button>';
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
