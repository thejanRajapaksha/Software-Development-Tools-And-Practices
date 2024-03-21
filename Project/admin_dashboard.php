<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin_dashboard.css"> <!-- Add your admin dashboard CSS file -->
    <script>
        function showRegistrationForm(userType) {
            window.location.href = userType + ".html";
        }
    </script>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>

    <!-- Navbar Start -->
    <center>
        <nav>
            <ul>
              <li><a href="index.php">Home</a></li>
              <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </center>
    <!-- Navbar End -->
    
    <main>
        <h2>User Registration</h2>
        <button onclick="showRegistrationForm('student')">Student </button>
        <button onclick="showRegistrationForm('warden')">Warden </button>
        <button onclick="showRegistrationForm('landlord')">Landlord </button>
    </main>
    <footer>
        <p>&copy; 2024 NSBM Green University</p>
    </footer>
</body>
</html>
