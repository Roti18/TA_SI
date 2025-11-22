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

// Ambil data kriteria
$query = "SELECT * FROM kriteria ORDER BY id ASC";
$result = $conn->query($query);
$kriterias = $result->fetch_all(MYSQLI_ASSOC);

// Ambil data subkriteria untuk setiap kriteria
$subkriterias = [];
foreach ($kriterias as $kriteria) {
  $stmt = $conn->prepare("SELECT * FROM sub_kriteria WHERE kriteria_id = ? ORDER BY rating DESC");
  $stmt->bind_param("i", $kriteria['id']);
  $stmt->execute();
  $subResult = $stmt->get_result();
  $subkriterias[$kriteria['id']] = $subResult->fetch_all(MYSQLI_ASSOC);
}

$title = "Data Kriteria";

if (!isset($_SESSION['userdata'])) {
  header("Location: index.php?page=login");
  exit;
}
?>

<div class="ml-64 flex min-h-screen bg-gray-100">

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

    <!-- Kriteria Cards -->
    <?php foreach ($kriterias as $index => $kriteria): ?>
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6 kriteria-card">
        <!-- Header Kriteria -->
        <div class="flex justify-between items-center mb-4 pb-4 border-b">
          <div>
            <h2 class="text-xl font-bold text-gray-800">
              <span
                class="bg-blue-500 text-white px-3 py-1 rounded-lg mr-2"><?= htmlspecialchars($kriteria['kode']) ?></span>
              <?= htmlspecialchars($kriteria['nama']) ?>
            </h2>
            <div class="mt-2 flex gap-4 text-sm text-gray-600">
              <span>Bobot: <strong><?= htmlspecialchars($kriteria['bobot']) ?></strong></span>
              <span
                class="<?= $kriteria['jenis'] === 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> text-xs font-semibold px-2.5 py-0.5 rounded-full">
                <?= ucfirst(htmlspecialchars($kriteria['jenis'])) ?>
              </span>
            </div>
          </div>
          <div class="flex gap-2">
            <a href="updatekriteria?id=<?= $kriteria['id'] ?>"
              class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg transition duration-300"
              title="Edit Kriteria">
              <i data-lucide="edit" class="w-4 h-4"></i>
            </a>
            <a href="hapuskriteria?action=delete&id=<?= $kriteria['id'] ?>"
              onclick="return confirm('Apakah Anda yakin ingin menghapus kriteria ini beserta subkriterianya?');"
              class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition duration-300"
              title="Hapus Kriteria">
              <i data-lucide="trash-2" class="w-4 h-4"></i>
            </a>
            <a href="tambahsubkriteria?kriteria_id=<?= $kriteria['id'] ?>"
              class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg transition duration-300"
              title="Tambah Subkriteria">
              <i data-lucide="plus" class="w-4 h-4"></i>
            </a>
          </div>
        </div>

        <!-- Tabel Subkriteria -->
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white">
            <thead class="bg-gray-50">
              <tr>
                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Min Value</th>
                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Max Value</th>
                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                <th class="py-2 px-4 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
              </tr>
            </thead>
            <tbody class="text-gray-700">
              <?php if (!empty($subkriterias[$kriteria['id']])): ?>
                <?php foreach ($subkriterias[$kriteria['id']] as $subIndex => $sub): ?>
                  <tr class="hover:bg-gray-50 border-b">
                    <td class="py-3 px-4"><?= $subIndex + 1 ?></td>
                    <td class="py-3 px-4 font-medium"><?= htmlspecialchars($sub['nama']) ?></td>
                    <td class="py-3 px-4"><?= $sub['min_value'] !== null ? htmlspecialchars($sub['min_value']) : '-' ?></td>
                    <td class="py-3 px-4"><?= $sub['max_value'] !== null ? htmlspecialchars($sub['max_value']) : '-' ?></td>
                    <td class="py-3 px-4">
                      <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                        <?= htmlspecialchars($sub['rating']) ?>
                      </span>
                    </td>
                    <td class="py-3 px-4">
                      <div class="flex items-center justify-center gap-2">
                        <a href="updatesub-kriteria?id=<?= $sub['id'] ?>"
                          class="text-yellow-500 hover:text-yellow-600 transition duration-300" title="Edit">
                          <i data-lucide="edit" class="w-4 h-4"></i>
                        </a>
                        <a href="hapussub-kriteria?action=delete&id=<?= $sub['id'] ?>"
                          onclick="return confirm('Apakah Anda yakin ingin menghapus subkriteria ini?');"
                          class="text-red-500 hover:text-red-600 transition duration-300" title="Hapus">
                          <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center py-6 text-gray-400">
                    Belum ada subkriteria.
                    <a href="tambahsubkriteria?kriteria_id=<?= $kriteria['id'] ?>" class="text-blue-500 hover:underline">
                      Tambah sekarang
                    </a>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (empty($kriterias)): ?>
      <div class="bg-white rounded-lg shadow-lg p-6 text-center text-gray-500">
        <p>Tidak ada data kriteria.</p>
      </div>
    <?php endif; ?>

  </div>

  <?php include 'includes/footer.php'; ?>

</div>

<script>
  document.getElementById('searchInput').addEventListener('keyup', function() {
    const keyword = this.value.toLowerCase();
    const cards = document.querySelectorAll('.kriteria-card');

    cards.forEach(card => {
      const text = card.textContent.toLowerCase();
      card.style.display = text.includes(keyword) ? '' : 'none';
    });
  });
</script>