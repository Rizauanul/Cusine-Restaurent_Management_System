<?php
$servername = "localhost";
$username = "root";     
$password = "";        
$dbname = "cuisine_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(503);
    exit("Database Connection Failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name  = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $subject    = mysqli_real_escape_string($conn, $_POST['subject']);
    $message    = mysqli_real_escape_string($conn, $_POST['message']);

    if (empty($first_name) || empty($last_name) || empty($email) || empty($subject) || empty($message)) {
        http_response_code(400);
        exit("All fields are required.");
    }
    $stmt = $conn->prepare("INSERT INTO contact_inquiries (first_name, last_name, email, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $subject, $message);

    if ($stmt->execute()) {
        http_response_code(200);
        echo "Success";
    } else {
        http_response_code(500);
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
} else {
    http_response_code(403);
    echo "Direct access not allowed.";
}
?>