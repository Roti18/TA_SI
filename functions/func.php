<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once "D:/Xampp/htdocs/TugasAkhirSI/config/connect.php";
include_once "D:/Xampp/htdocs/TugasAkhirSI/config/helpers.php";

$action = $_GET['action'] ?? null;

// Function to redirect with a message
function redirect_with_message($url, $message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: $url");
    exit();
}

// Main controller logic
if ($action === 'create') {
    // Handle Create Logic
    $nis = $_POST['nis'] ?? '';
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $id_kelas = $_POST['id_kelas'] ?? '';

    if (empty($nis) || empty($nama_lengkap) || empty($alamat) || empty($id_kelas)) {
        redirect_with_message(getBaseUrl() . 'views/siswa/create.php', 'Semua field harus diisi.', 'error');
    }

    $stmt = $conn->prepare("INSERT INTO siswa (nis, nama_lengkap, alamat, id_kelas) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nis, $nama_lengkap, $alamat, $id_kelas);
    
    if ($stmt->execute()) {
        redirect_with_message(getBaseUrl() . 'views/siswa/index.php', 'Data siswa berhasil ditambahkan.');
    } else {
        redirect_with_message(getBaseUrl() . 'views/siswa/create.php', 'Gagal menambahkan data: ' . $stmt->error, 'error');
    }
    $stmt->close();

} elseif ($action === 'update') {
    // Handle Update Logic
    $id = $_POST['id'] ?? '';
    $nis = $_POST['nis'] ?? '';
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $id_kelas = $_POST['id_kelas'] ?? '';

    if (empty($id) || empty($nis) || empty($nama_lengkap) || empty($alamat) || empty($id_kelas)) {
        redirect_with_message(getBaseUrl() . 'views/siswa/edit.php?id=' . $id, 'Semua field harus diisi.', 'error');
    }

    $stmt = $conn->prepare("UPDATE siswa SET nis = ?, nama_lengkap = ?, alamat = ?, id_kelas = ? WHERE id = ?");
    $stmt->bind_param("sssii", $nis, $nama_lengkap, $alamat, $id_kelas, $id);

    if ($stmt->execute()) {
        redirect_with_message(getBaseUrl() . 'views/siswa/index.php', 'Data siswa berhasil diperbarui.');
    } else {
        redirect_with_message(getBaseUrl() . 'views/siswa/edit.php?id=' . $id, 'Gagal memperbarui data: ' . $stmt->error, 'error');
    }
    $stmt->close();

} elseif ($action === 'delete') {
    // Handle Delete Logic
    $id = $_GET['id'] ?? '';

    if (empty($id)) {
        redirect_with_message(getBaseUrl() . 'views/siswa/index.php', 'ID siswa tidak valid.', 'error');
    }

    $stmt = $conn->prepare("DELETE FROM siswa WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        redirect_with_message(getBaseUrl() . 'views/siswa/index.php', 'Data siswa berhasil dihapus.');
    } else {
        redirect_with_message(getBaseUrl() . 'views/siswa/index.php', 'Gagal menghapus data: ' . $stmt->error, 'error');
    }
    $stmt->close();
}

$conn->close();
?>
