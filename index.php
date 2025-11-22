<?php
session_start();

// 1. Tentukan default route
// jika belum login → home
// jika sudah login → dashboard
if (!isset($_SESSION['userdata'])) {
    $defaultPage = 'home';
} else {
    $defaultPage = 'dashboard';
}

// 2. Ambil route dari GET atau gunakan default
$request = $_GET['page'] ?? $defaultPage;

// 3. Daftar route
$routes = [
    'home'          => 'views/home.php',
    'dashboard'     => 'views/admin/dashboard.php',
    'login'         => 'views/auth/login.php',
    'proses-login'  => 'views/auth/proses-login.php',
    'logout'        => 'views/auth/logout.php',
    'profile'       => 'views/user/profil.php',

    'siswa'         => 'views/siswa/index.php',
    'tambahsiswa'   => 'views/siswa/create.php',
    'updatesiswa'   => 'views/siswa/edit.php',
    'hapussiswa'    => 'views/siswa/delete.php',

    'kriteria'      => 'views/kriteria/index.php',
    'tambahkriteria'=> 'views/kriteria/create.php',
    'updatekriteria'=> 'views/kriteria/edit.php',
    'hapuskriteria' => 'views/kriteria/delete.php',

    'sub-kriteria'      => 'views/sub_kriteria/index.php',
    'tambahsub-kriteria'=> 'views/sub_kriteria/create.php',
    'updatesub-kriteria'=> 'views/sub_kriteria/edit.php',
    'hapussub-kriteria' => 'views/sub_kriteria/delete.php',

    'penilaian'      => 'views/penilaian/index.php',
    'tambahpenilaian'=> 'views/penilaian/create.php',
];

// 4. Cegah user masuk dashboard tanpa login
if ($request === 'dashboard' && !isset($_SESSION['userdata'])) {
    header("Location: index.php?page=login");
    exit;
}

// 5. Cek apakah route ada
if (!array_key_exists($request, $routes)) {
    include __DIR__ . "/views/404.php";
    exit;
}

// 6. Include view
include $routes[$request];