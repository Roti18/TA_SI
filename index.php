<?php
$request = isset($_GET['page']) ? $_GET['page'] : 'home';

$routes = [
    'home'      => 'home.php',
    'dashboard' => 'admin.php',
    'login'     => 'login.php',
    'profile'   => 'user/profil.php'
];

if (array_key_exists($request, $routes)) {
    include $routes[$request];
} else {
    http_response_code(404);
    echo "<h1>Halaman tidak ditemukan! (404)</h1>";
}
?>
<?php require_once 'includes/header.php'; ?>

<h1 class="text-3xl font-bold underline text-center text-red-500">
  Hello world!
</h1>

<?php require_once 'includes/footer.php'; ?>