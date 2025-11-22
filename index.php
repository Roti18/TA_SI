<?php
$request = isset($_GET['page']) ? $_GET['page'] : 'home';

$routes = [
    'home'      => 'views/home.php',
    'dashboard' => 'views/admin.php',
    'login'     => 'views/login.php',
    'profile'   => 'views/user/profil.php'
];

if (array_key_exists($request, $routes)) {
    include $routes[$request];
} else {
    http_response_code(404);
    echo "<h1>Halaman tidak ditemukan! (404)</h1>";
}
?>