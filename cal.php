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

// Memeriksa jika ada sesi email
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Melakukan query ke database untuk mendapatkan informasi nama_dosen berdasarkan email
    $sql = "SELECT nama_dosen FROM tbdosen WHERE email_dosen = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nama_dosen = $row['nama_dosen'];
    }
} else {
    // Jika tidak ada sesi email, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Menutup koneksi database
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
  <title>Kalender Kegiatan</title>
  <link href="cal.css" rel="stylesheet" type="text/css">
  <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
<!-- Link untuk Font Awesome -->
<link rel="stylesheet" href="fontawesome-free-6.4.2-web/css/all.css">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.js"></script>
    <script src="sweetalert.min.js"></script>
    <!-- CSS untuk styling -->
    <link href="https://fonts.googleapis.com/css2?family=Grenze&family=Hind+Siliguri:wght@500;600&family=Hubballi&family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&family=Young+Serif&display=swap" rel="stylesheet">
</head>
<body>

<div class="navbar">
        <a href="#" class="navbar-logo">MySchedule</a>

        <div class="navbar-extra">
            <div>
                <p id="username"><?php echo $nama_dosen; ?></p>
                <p id="email"><?php echo $email; ?></p>
            </div>

            <form onsubmit="return submitForm(this);" action="login.php">
            <button type="submit" id="logout-icon">
                <i class="fas fa-arrow-right-from-bracket"></i>
            </button>
        </form>
        </div>
</div>


  <div id='calendar'></div>
  <div id='info'></div>

  <script>
    $(document).ready(function() {
      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,basicWeek,basicDay'
        },
        dayRender: function(date, cell) {
  var tanggal = date.format('YYYY-MM-DD');

  $.ajax({
    url: 'cek_kegiatan.php',
    type: 'POST',
    data: {
      tanggal: tanggal
    },
    success: function(response) {
      if (response > 1) {
        cell.css('background-color', 'red');
      } else if (response == 1) {
        cell.css('background-color', 'lightgreen');
      } else {
        cell.css('background-color', 'white');
      }
    },
    error: function(xhr, status, error) {
      console.error('Terjadi kesalahan: ' + error);
    }
  });
},

        dayClick: function(date, jsEvent, view) {
          var tanggal = date.format('YYYY-MM-DD');
          
          // Mengirim permintaan AJAX untuk mendapatkan pesan pada tanggal tertentu
          $.ajax({
            url: 'ambil_kegiatan.php', // File PHP untuk mengambil pesan dari database
            type: 'POST',
            data: {
              tanggal: tanggal
            },
            success: function(response) {
              var info = $('#info');
              info.empty(); // Membersihkan info sebelum menampilkan data baru

              if (response.length > 0) {
                // Jika terdapat pesan, tampilkan pesan di bawah kalender
                var pesan = '<h3>Pesan pada tanggal ' + tanggal + ':</h3>';
                pesan += '<ul>';
                $.each(response, function(index, item) {
                  pesan += '<li><strong>Pengirim:</strong> ' + item.pengirim + '</li>';
                  pesan += '<li><strong>Kegiatan:</strong> ' + item.kegiatan + '</li>';
                  pesan += '<li><strong>Nama Dosen:</strong> ' + item.nama_dosen + '</li>';
                  pesan += '<li><strong>Tanggal:</strong> ' + item.tanggal + '</li>';
                  pesan += '<li><strong>Waktu:</strong> ' + item.waktu + '</li>';
                  pesan += '<div class="message-space"></div>';
                });
                pesan += '</ul>';

                info.append(pesan); // Menampilkan pesan di bawah kalender
              } else {
                // Jika tidak ada pesan, tampilkan pesan bahwa tidak ada kegiatan
                info.text('Tidak ada kegiatan pada tanggal ' + tanggal);
              }
            },
            error: function(xhr, status, error) {
              console.error('Terjadi kesalahan: ' + error);
            }
          });
        }
      });
    });
  </script>

<script>
    function submitForm(form) {
       swal({
        title: "Apakah anda yakin akan keluar?",
        text: "Anda akan keluar dari website",
        icon: "warning",
        buttons: true,
        dangerMode: true,
       })
       .then((isOkay) =>{
        if (isOkay){
            form.submit();
        }
       }
       
       );
       return false;
    }
</script>

</body>
</html>