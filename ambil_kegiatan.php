<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myschedule";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menerima tanggal dari permintaan AJAX
$tanggal = $_POST['tanggal'];

// Query untuk mengambil kegiatan dari database berdasarkan tanggal
$sql = "SELECT * FROM kirim WHERE tanggal = '$tanggal'";
$result = $conn->query($sql);

// Mengubah data dari database ke format yang dapat digunakan oleh JavaScript
$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Mengirim data kegiatan dalam format JSON
header('Content-Type: application/json');
echo json_encode($data);

// Tutup koneksi database
$conn->close();
?>
