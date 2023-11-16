<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$admin_name = $_POST['admin_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'gkt_restaurant');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if username already exists
    $stmt_check = $conn->prepare("SELECT admin_id FROM admin WHERE username = ?");
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        echo "<script>alert('Username already exists. Try again with Different one'); window.location.href='admin_register.html';</script>";
        exit;
    }
    $stmt_check->close();

    // Insert new admin
    $stmt = $conn->prepare("INSERT INTO admin (username, password, admin_name) VALUES (?, ?, ?)");
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("sss", $username, $hashed_password, $admin_name);
    $stmt->execute();

    echo "<script>alert('Registration successful.'); window.location.href='admin_login.html';</script>";

    $stmt->close();
    $conn->close();
}
?>
