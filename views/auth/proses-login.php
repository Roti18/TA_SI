<?php
session_start();
require "config/connect.php";
require "config/helpers.php";

var_dump($_POST);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

// Ambil user berdasarkan email
$query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    // Cek password
    if (password_verify($password, $user['password'])) {

        // Login sukses → simpan session
        $_SESSION['userdata'] = [
            "id"    => $user['id'],
            "email" => $user['email'],
            "nama"  => $user['nama']
        ];

        redirect("dashboard");
    }
}

// Jika gagal
$_SESSION['error'] = "Email atau password salah!";
redirect("login");
