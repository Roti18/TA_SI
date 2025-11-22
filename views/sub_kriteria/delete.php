<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../functions/func.php';

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $where = ['id' => $_GET['id']];
    deleteData('sub_kriteria', $where, 'sub-kriteria', 'kriteria');
}

header("Location: sub-kriteria");
exit;