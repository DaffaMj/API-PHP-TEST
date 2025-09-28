<?php
// config.php
$host = "localhost";
$db   = "simple_api";
$user = "root";   // sesuaikan jika berbeda
$pass = "";       // sesuaikan jika berbeda

// gunakan mysqli (sederhana)
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Koneksi database gagal: " . $conn->connect_error]);
    exit;
}
?>
