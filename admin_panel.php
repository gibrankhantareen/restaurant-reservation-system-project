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
    <!-- Admin Navigation Bar -->
    <div class="admin-nav">
        <a href="admin_panel.php">Dashboard</a>
        <a href="manage_booking.php">Manage Bookings</a>
        <a href="send_reminders.php">Send Email Reminders</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <div class="admin-container">
        <h1>Admin Panel</h1>

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
        <button onclick="window.location.href='manage_booking.php'">Edit Booking</button>
        <button onclick="sendEmailReminders()">Send Email Reminders</button>
        <script>
            function sendEmailReminders() {
                alert("Reminder email sent to all guests");
            }
        </script>
        <button onclick="window.location.href='admin_logout.php'">Logout</button>
    </div>
</body>
</html>