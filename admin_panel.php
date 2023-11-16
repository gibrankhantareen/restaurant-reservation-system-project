<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html");
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'gkt_restaurant');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch guests data
$guests_result = $conn->query("SELECT * FROM guests");

// Fetch reservations data
$reservations_result = $conn->query("SELECT * FROM reservations");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="./css/admin_panel.css">
</head>
<body>
    <!-- Gibran Restaurant ka Admin Navigation Bar -->
    <div class="admin-nav" id="admin-nav">
		<a href="javascript:void(0);" class="icon" onclick="toggleMenu()">≡</a>
        <a href="admin_panel.php">Dashboard</a>
        <a href="#" onclick="manageBookingAlert()">Manage Bookings</a>
        <a href="#" onclick="manageBookingAlert()">Send Email Reminders</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <div class="admin-container">
        <h1 style="text-align: center;">Welcome to Gibran's Delight - Admin Panel</h1>

        <h2>Guests</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
            </tr>
            <?php while ($guest = $guests_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $guest['guest_id']; ?></td>
                    <td><?php echo $guest['name']; ?></td>
                    <td><?php echo $guest['email']; ?></td>
                    <td><?php echo $guest['phone_number']; ?></td>
                </tr>
            <?php } ?>
        </table>

        <h2>Reservations</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Guest ID</th>
                <th>Number of Guests</th>
                <th>Date</th>
                <th>Amount Due</th>
            </tr>
            <?php while ($reservation = $reservations_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $reservation['reservation_id']; ?></td>
                    <td><?php echo $reservation['guest_id']; ?></td>
                    <td><?php echo $reservation['num_guests']; ?></td>
                    <td><?php echo $reservation['reservation_date']; ?></td>
                    <td><?php echo $reservation['amount_due']; ?></td>
                </tr>
            <?php } ?>
        </table>

        <!-- Management Buttons -->
        <button onclick="manageBookingAlert()">Edit Booking</button>
        <button onclick="sendEmailReminders()">Send Email Reminders</button>

		<!-- JS script which has the funcs -->
        <script src="./js/admin_panel.js"></script>

        <button onclick="window.location.href='admin_logout.php'">Logout</button>
    </div>
</body>
</html>
