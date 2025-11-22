<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../../functions/func.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'delete' && isset($_GET['id'])) {
        $where = ['id' => $_GET['id']];
        deleteData('kriteria', $where, 'kriteria', 'kriteria');
    }
}

header("Location: kriteria");
exit;
?>