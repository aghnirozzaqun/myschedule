<?php
session_start(); // Mulai sesi, diperlukan untuk menyimpan informasi login jika berhasil

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

// Inisialisasi variabel untuk pesan
$pesan = "";

// Memeriksa jika form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mendapatkan nilai dari form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Melakukan query ke database untuk memeriksa keberadaan email dan password
    $sql = "SELECT * FROM tbdosen WHERE email_dosen = '$email' AND password_dosen = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Jika data ditemukan, artinya email dan password cocok
        $_SESSION['email'] = $email; // Menyimpan email dalam sesi
        header("Location: cal.php"); // Redirect ke halaman kalender setelah login berhasil
        exit();
    } else {
        // Jika data tidak ditemukan, atur pesan untuk ditampilkan di halaman login
        $pesan = "Akun belum terdaftar. Silakan coba lagi atau <a href='daftar.php'>daftar</a> jika belum memiliki akun.";
    }
}

// Menutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>My Schedule</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@500;600&family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet">
</head>
<body>
<div class="login-container">
        <h2>Masuk</h2>
        <?php if (!empty($pesan)) { ?>
            <p><?php echo $pesan; ?></p>
        <?php } ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Kata sandi</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="button-container">
                <button type="submit" name="masuk">Masuk</button>
            </div>
        </form>
        <p>Belum memiliki akun? <a href="daftar.php">Daftar</a></p>
    </div>
</body>
</html>
