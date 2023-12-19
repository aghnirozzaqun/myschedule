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

// Query untuk memeriksa jumlah kegiatan dari database berdasarkan tanggal
$sql = "SELECT COUNT(*) as count FROM kirim WHERE tanggal = '$tanggal'";
$result = $conn->query($sql);

// Mengembalikan jumlah kegiatan pada tanggal tersebut
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['count'];
} else {
    echo "0";
}

// Tutup koneksi database
$conn->close();
?>
