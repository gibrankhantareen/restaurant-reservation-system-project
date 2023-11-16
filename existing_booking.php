<?php
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'gkt_restaurant');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch existing reservations for the email
    $stmt = $conn->prepare("SELECT r.reservation_id, r.reservation_date, g.name, r.amount_due FROM reservations r JOIN guests g ON r.guest_id = g.guest_id WHERE g.email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

	echo "<link rel='stylesheet' type='text/css' href='./css/table-styles.css'>";

    if ($result->num_rows > 0) {
        // Display existing reservations
        echo "<p>Existing reservations for {$email}:</p>";
        echo "<table border='1'>
                <tr>
                    <th>Reservation ID</th>
                    <th>Date of Event</th>
                    <th>Guest Name</th>
                    <th>Amount Due</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['reservation_id']}</td>
                    <td>{$row['reservation_date']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['amount_due']}</td>
                  </tr>";
        }
        echo "</table>";
        echo "<button onclick='window.location.href=\"http://localhost/hotel-reservation-system/reservation.html\"'>Home</button>";
    } else {
        echo "<p>No existing reservations found for this email.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
