<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'gkt_restaurant');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check credentials
    $stmt = $conn->prepare("SELECT admin_id, password, admin_name FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, start a new session
            $_SESSION['admin_id'] = $row['admin_id'];
			$_SESSION['admin_name']= $row['admin_name'];
            $_SESSION['username'] = $username;

            // Redirect to admin panel
            header("Location: admin_panel.php");
            exit;
        } else {
            // Password is not correct
            echo "<script>alert('Invalid password.'); window.location.href='admin_login.html';</script>";
        }
    } else {
        // No such user exists
        echo "<script>alert('Invalid username.'); window.location.href='admin_login.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
