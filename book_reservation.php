<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'gkt_restaurant');

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Input validation
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $guests = intval($_POST['guests']);
    $tables = intval($_POST['tables']);
    $amount_due = $tables * 1000;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location.href='http://localhost/hotel-reservation-system/reservation.html';</script>";
        exit;
    }

    if (!preg_match('/^\d{10}$/', $phone)) {
        echo "<script>alert('Phone number must be 10 digits.'); window.location.href='http://localhost/hotel-reservation-system/reservation.html';</script>";
        exit;
    }

    $currentDateTime = new DateTime();
	$reservationDateTime = new DateTime($date . ' ' . $time);

	if ($reservationDateTime < $currentDateTime) {
		echo "<script>alert('The date and time cannot be in the past.'); window.location.href='http://localhost/hotel-reservation-system/reservation.html';</script>";
		exit;
	}

	// Check if email already exists
    $stmt_check_email = $conn->prepare("SELECT guest_id FROM guests WHERE email = ?");
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();

    if ($result_check_email->num_rows > 0) {
        // Email exists
        echo "<script>
            if (confirm('A booking with this email already exists. Do you want to check your existing booking?')) {
                window.location.href='existing_booking.php?email=$email';
            } else {
                window.location.href='http://localhost/hotel-reservation-system/reservation.html';
            }
        </script>";
        exit;
    }
    $stmt_check_email->close();

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert guest details
        $stmt_guests = $conn->prepare("INSERT INTO guests (name, email, phone_number) VALUES (?, ?, ?)");
        $stmt_guests->bind_param("sss", $name, $email, $phone);
        $stmt_guests->execute();
        $guest_id = $conn->insert_id;

        // Insert reservation details
        $stmt_reservations = $conn->prepare("INSERT INTO reservations (guest_id, num_guests, reservation_date, num_tables, amount_due) VALUES (?, ?, ?, ?, ?)");
        $stmt_reservations->bind_param("iisii", $guest_id, $guests, $date, $tables, $amount_due);
        $stmt_reservations->execute();
        $reservation_id = $conn->insert_id;

        // Commit transaction
        $conn->commit();

        // Fetch reservation details
        $query = "SELECT r.reservation_id, g.email, g.name, r.num_guests, r.num_tables, r.reservation_date, r.amount_due, r.booking_time
                  FROM reservations r
                  JOIN guests g ON r.guest_id = g.guest_id
                  WHERE r.reservation_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservation_details = $result->fetch_assoc();

		echo "<link rel='stylesheet' type='text/css' href='./css/table-styles.css'>";

        // Display reservation details
        if ($reservation_details) {
            echo "<p>Dear {$reservation_details['name']}, your booking is confirmed. Check details below.</p>";
            echo "<table border='1'>
                <tr>
                    <th>Reservation ID</th>
					<th>Email-ID</th>
                    <th>Guest Name</th>
                    <th>Number of Guests</th>
                    <th>Number of Tables</th>
                    <th>Date and Time of Event</th>
                    <th>Booked On</th>
                    <th>Amount Due</th>
                </tr>
                <tr>
                    <td>{$reservation_details['reservation_id']}</td>
					<td>{$reservation_details['email']}</td>
                    <td>{$reservation_details['name']}</td>
                    <td>{$reservation_details['num_guests']}</td>
                    <td>{$reservation_details['num_tables']}</td>
                    <td>{$reservation_details['reservation_date']}</td>
                    <td>{$reservation_details['booking_time']}</td>
                    <td>{$reservation_details['amount_due']}</td>
                </tr>
              </table>";
            echo "<button onclick='sendEmailConfirmation()'>Send a Copy to Email</button>";
            echo "<button onclick='window.location.href=\"http://localhost/hotel-reservation-system/reservation.html\"'>Home</button>";
            echo "<script>
                    function sendEmailConfirmation() {
                        alert('Dear {$reservation_details['name']}, an Email has been sent successfully to {$reservation_details['email']}');
                        window.location.href='http://localhost/hotel-reservation-system';
                    }
                  </script>";
        } else {
            echo "<script>alert('There was an error fetching your reservation details.');</script>";
        }

        $stmt->close();
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        echo "<script>alert('There was an error processing your reservation.');</script>";
    }

    $conn->close();
} else {
    echo "No data submitted";
}
?>
