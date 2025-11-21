<?php 
$host = "localhost";
$user = "root";
$pass = ""; 
$dbnm = "";

$conn = mysqli_connect($host, $user, $pass, $dbnm); 

if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>