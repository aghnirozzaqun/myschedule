<?php
    $koneksi = mysqli_connect('localhost', 'root', '', 'myschedule');
    if(!$koneksi){
        echo "Koneksi gagal";
    } 
?>