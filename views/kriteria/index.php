<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../config/connect.php';
include __DIR__ . '/../../functions/func.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($conn->connect_error) {
    die("DEBUG: Koneksi Database Gagal: " . $conn->connect_error);
}

$query = "SELECT * FROM kriteria ORDER BY id ASC";
$result = $conn->query($query);

if (!$result) {
    die("DEBUG: Error pada Query: '" . $query . "'. Pesan Error: " . $conn->error);
}

$kriterias = $result->fetch_all(MYSQLI_ASSOC);

$title = "Data Kriteria";

if (!isset($_SESSION['userdata'])) {
    header("Location: index.php?page=login");
    exit;
}
?>

<div class="min-h-screen flex bg-gray-100">

  <?php include 'includes/sidebar.php'; ?>
  <?php include "includes/header.php"; ?>

  <div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Data Kriteria</h1>
      <a href="tambahkriteria"
        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
        <i data-lucide="plus" class="inline-block w-5 h-5 mr-2"></i>
        Tambah Kriteria
      </a>
    </div>

    <?php include "includes/notification.php"; ?>

    <!-- Search Bar -->
    <div class="mb-6">
      <div class="relative">
        <input type="text" id="searchInput" placeholder="Cari kriteria..."
          class="w-full pl-10 pr-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <i data-lucide="search" class="text-gray-400"></i>
        </div>
      </div>
    </div>

    <!-- Kriteria Table -->
    <div class="bg-white rounded-lg shadow-lg p-6">
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
          <thead class="bg-gray-100">
            <tr>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
              <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
              <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>

          <tbody class="text-gray-700">
            <?php if (!empty($kriterias)): ?>
            <?php foreach ($kriterias as $index => $kriteria): ?>
            <tr class="hover:bg-gray-50 border-b">
              <td class="py-4 px-6"><?= $index + 1 ?></td>
              <td class="py-4 px-6 font-medium"><?= htmlspecialchars($kriteria['kode']) ?></td>
              <td class="py-4 px-6"><?= htmlspecialchars($kriteria['nama']) ?></td>
              <td class="py-4 px-6"><?= htmlspecialchars($kriteria['bobot']) ?></td>
              <td class="py-4 px-6">
                <span
                  class="<?= $kriteria['jenis'] === 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> text-xs font-semibold px-2.5 py-0.5 rounded-full">
                  <?= htmlspecialchars($kriteria['jenis']) ?>
                </span>
              </td>
              <td class="py-4 px-6">
                <div class="flex items-center justify-center gap-3">
                  <a href="updatekriteria?id=<?= $kriteria['id'] ?>"
                    class="text-yellow-500 hover:text-yellow-600 transition duration-300" title="Edit">
                    <i data-lucide="edit" class="w-5 h-5"></i>
                  </a>
                  <a href="hapuskriteria?action=delete&id=<?= $kriteria['id'] ?>"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"
                    class="text-red-500 hover:text-red-600 transition duration-300" title="Hapus">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
              <td colspan="6" class="text-center py-10 text-gray-500">
                <p>Tidak ada data kriteria.</p>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>

</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
  const keyword = this.value.toLowerCase();
  const rows = document.querySelectorAll('tbody tr');

  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(keyword) ? '' : 'none';
  });
});
</script>