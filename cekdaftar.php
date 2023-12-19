<?php
// Pastikan untuk mengisi informasi koneksi database Anda
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myschedule";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Memeriksa jika form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mendapatkan nilai dari form
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Menyimpan data ke dalam tabel tbdosen
    $sql = "INSERT INTO tbdosen (nip, nama_dosen, email_dosen, password_dosen)
    VALUES ('$nip', '$nama', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    // Jika data berhasil disimpan ke dalam database
    header("Location: login.php");
    exit(); // Pastikan untuk keluar setelah melakukan redirect
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}

// Menutup koneksi database
$conn->close();
?>