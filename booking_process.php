<?php
$servername = "localhost";
$username = "root";    
$password = "";        
$dbname = "cuisine_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    exit("Database connection failed.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $first_name   = trim($_POST['first_name'] ?? '');
    $last_name    = trim($_POST['last_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $table_type   = $_POST['table_type'] ?? '';
    $guest_number = intval($_POST['guest_number'] ?? 0);
    $placement    = $_POST['placement'] ?? '';
    $date         = $_POST['date'] ?? '';
    $time         = $_POST['time'] ?? '';
    $note         = trim($_POST['note'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($email) || empty($table_type) || $guest_number <= 0 || empty($placement) || empty($date) || empty($time)) {
        http_response_code(400);
        exit("Please fill in all required fields properly.");
    }


    $stmt = $conn->prepare("INSERT INTO table_bookings (first_name, last_name, email, table_type, guest_number, placement, booking_date, booking_time, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssissss", $first_name, $last_name, $email, $table_type, $guest_number, $placement, $date, $time, $note);

    if ($stmt->execute()) {
        
        http_response_code(200);
        echo "success";
    } else {
        http_response_code(500);
        echo "Something went wrong on the server: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo "Method Not Allowed";
}
?>