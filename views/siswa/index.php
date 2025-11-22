<?php
require 'config/connect.php';

ini_set('display_errors', 1);
if ($conn->connect_error) {
    die("DEBUG: Koneksi Database Gagal: " . $conn->connect_error);
}

$query = "SELECT * FROM siswa ORDER BY nama ASC";
$result = $conn->query($query);

if (!$result) {
    die("DEBUG: Error pada Query: '" . $query . "'. Pesan Error: " . $conn->error);
}

$students = $result->fetch_all(MYSQLI_ASSOC);

$message = $_SESSION['message'] ?? null;
$message_type = $_SESSION['message_type'] ?? null;
unset($_SESSION['message'], $_SESSION['message_type']);

ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = "Data Siswa";

if (!isset($_SESSION['userdata'])) {
    header("Location: index.php?page=login");
    exit;
}

global $request;
?>

<div class="min-h-screen flex bg-gray-100">

  <!-- Sidebar -->
  <?php include 'includes/sidebar.php'; ?>

  <?php include "includes/header.php"; ?>

  <div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Data Siswa</h1>
      <a href="create.php"
        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
        <i data-lucide="plus" class="inline-block w-5 h-5 mr-2"></i>
        Tambah Siswa
      </a>
    </div>

    <?php if ($message): ?>
    <div
      class="mb-4 p-4 rounded-lg text-white <?php echo ($message_type === 'success') ? 'bg-green-500' : 'bg-red-500'; ?>">
      <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <!-- Search Bar -->
    <div class="mb-6">
      <div class="relative">
        <input type="text" placeholder="Cari siswa..."
          class="w-full pl-10 pr-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <i data-lucide="search" class="text-gray-400"></i>
        </div>
      </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-lg shadow-lg p-6">
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
          <thead class="bg-gray-100">
            <tr>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
              <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>

          <tbody class="text-gray-700">
            <?php if (!empty($students)): ?>
            <?php foreach ($students as $index => $student): ?>
            <tr class="hover:bg-gray-50 border-b">
              <td class="py-4 px-6"><?= $index + 1 ?></td>
              <td class="py-4 px-6"><?= htmlspecialchars($student['nis']) ?></td>
              <td class="py-4 px-6 font-medium"><?= htmlspecialchars($student['nama']) ?></td>
              <td class="py-4 px-6">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded-full">
                  <?= htmlspecialchars($student['kelas']) ?>
                </span>
              </td>
              <td class="py-4 px-6 text-center">
                <a href="edit.php?id=<?= $student['id'] ?>"
                  class="text-yellow-500 hover:text-yellow-600 mr-4 transition duration-300">
                  <i data-lucide="edit" class="w-5 h-5"></i>
                </a>
                <a href="../../functions/func.php?action=delete&id=<?= $student['id'] ?>"
                  onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"
                  class="text-red-500 hover:text-red-600 transition duration-300">
                  <i data-lucide="trash-2" class="w-5 h-5"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
              <td colspan="6" class="text-center py-10 text-gray-500">
                <p>Tidak ada data siswa.</p>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <?php include 'includes/footer.php'; ?>

</div>
</div>