<?php
// হেডার সেট করা যাতে জাভাস্ক্রিপ্ট এবং ডাটাটেবিল সঠিকভাবে JSON ডেটা পায়
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$servername = "localhost";
$username = "root";     
$password = "";         
$dbname = "cuisine_db"; 

// ডাটাবেজ কানেকশন
$conn = new mysqli($servername, $username, $password, $dbname);

// কানেকশন ফেল করলে এরর মেসেজ পাঠানো
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database Connection Failed: " . $conn->connect_error]);
    exit();
}

// অ্যাকশন ইউআরএল থেকে গেট করা
$action = $_GET['action'] ?? '';

if ($action === 'get_bookings') {
    // বুকিং টেবিল থেকে ডেটা কুয়েরি
    $sql = "SELECT id, first_name, last_name, email, table_type, guest_number, placement, booking_date, booking_time, note, created_at FROM table_bookings ORDER BY id DESC";
    $result = $conn->query($sql);
    
    $bookings = [];
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }
    echo json_encode($bookings);
    exit();

} elseif ($action === 'get_contacts') {
    // কন্টাক্ট টেবিল থেকে ডেটা কুয়েরি
    $sql = "SELECT * FROM contact_inquiries ORDER BY id DESC";
    $result = $conn->query($sql);
    
    $contacts = [];
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // ডাটাবেজে টাইমস্ট্যাম্প কলামের নাম যাই হোক না কেন (submitted_at/created_at), আমরা সেটিকে ফ্রন্টএন্ডের জন্য 'received_at' বানিয়ে দিচ্ছি
            $time = '';
            if (isset($row['submitted_at'])) { $time = $row['submitted_at']; }
            elseif (isset($row['created_at'])) { $time = $row['created_at']; }
            elseif (isset($row['date'])) { $time = $row['date']; }

            $contacts[] = [
                "id"          => $row['id'],
                "first_name"  => $row['first_name'] ?? '',
                "last_name"   => $row['last_name'] ?? '',
                "email"       => $row['email'] ?? '',
                "subject"     => $row['subject'] ?? '',
                "message"     => $row['message'] ?? '',
                "received_at" => $time
            ];
        }
    }
    
    // ডাটাবেজ থেকে যদি ফাঁকা অ্যারেও আসে, তাও JSON ফরম্যাটে রিটার্ন করবে যেন ডাটাটেবিল ক্র্যাশ না করে
    echo json_encode($contacts);
    exit();

} else {
    http_response_code(400);
    echo json_encode(["error" => "Invalid Action Specified"]);
    exit();
}

$conn->close();
?>