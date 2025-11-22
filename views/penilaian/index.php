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

// Ambil data siswa
$querySiswa = "SELECT * FROM siswa ORDER BY nama ASC";
$resultSiswa = $conn->query($querySiswa);
$siswas = $resultSiswa->fetch_all(MYSQLI_ASSOC);

// Ambil data kriteria
$queryKriteria = "SELECT * FROM kriteria ORDER BY id ASC";
$resultKriteria = $conn->query($queryKriteria);
$kriterias = $resultKriteria->fetch_all(MYSQLI_ASSOC);

// Ambil data penilaian dengan relasi
$penilaians = [];
foreach ($siswas as $siswa) {
    $stmt = $conn->prepare("
        SELECT p.*, k.kode, k.nama as kriteria_nama 
        FROM penilaian p 
        JOIN kriteria k ON p.kriteria_id = k.id 
        WHERE p.siswa_id = ? 
        ORDER BY k.id ASC
    ");
    $stmt->bind_param("i", $siswa['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $penilaians[$siswa['id']] = $result->fetch_all(MYSQLI_ASSOC);
}

$title = "Data Penilaian";

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
      <h1 class="text-3xl font-bold text-gray-800">Data Penilaian</h1>
    </div>

    <?php include "includes/notification.php"; ?>

    <!-- Search Bar -->
    <div class="mb-6">
      <div class="relative">
        <input type="text" id="searchInput" placeholder="Cari siswa..."
          class="w-full pl-10 pr-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <i data-lucide="search" class="text-gray-400"></i>
        </div>
      </div>
    </div>

    <!-- Penilaian Cards per Siswa -->
    <?php foreach ($siswas as $siswa): ?>
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6 penilaian-card">
      <!-- Header Siswa -->
      <div class="flex justify-between items-center mb-4 pb-4 border-b">
        <div>
          <h2 class="text-xl font-bold text-gray-800">
            <span class="bg-blue-500 text-white px-3 py-1 rounded-lg mr-2"><?= htmlspecialchars($siswa['nis']) ?></span>
            <?= htmlspecialchars($siswa['nama']) ?>
          </h2>
          <div class="mt-2 flex gap-4 text-sm text-gray-600">
            <span>Kelas: <strong><?= htmlspecialchars($siswa['kelas']) ?></strong></span>
            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
              <?= count($penilaians[$siswa['id']]) ?> / <?= count($kriterias) ?> Kriteria Dinilai
            </span>
          </div>
        </div>
        <div class="flex">
          <a href="updatepenilaian?siswa_id=<?= $siswa['id'] ?>"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg transition duration-300"
            title="Edit Penilaian">
            <i data-lucide="edit" class="w-4 h-4"></i>
          </a>
        </div>
      </div>

      <!-- Tabel Penilaian -->
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
          <thead class="bg-gray-50">
            <tr>
              <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
              <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
              <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
              <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai Input</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php if (!empty($penilaians[$siswa['id']])): ?>
            <?php foreach ($penilaians[$siswa['id']] as $index => $nilai): ?>
            <tr class="hover:bg-gray-50 border-b">
              <td class="py-2 px-3"><?= $index + 1 ?></td>
              <td class="py-2 px-3">
                <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-2 py-0.5 rounded">
                  <?= htmlspecialchars($nilai['kode']) ?>
                </span>
              </td>
              <td class="py-2 px-3 font-medium"><?= htmlspecialchars($nilai['kriteria_nama']) ?></td>
              <td class="py-2 px-3"><?= htmlspecialchars($nilai['nilai_input']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
              <td colspan="4" class="text-center py-4 text-gray-400">
                Belum ada penilaian.
                <a href="updatepenilaian?siswa_id=<?= $siswa['id'] ?>" class="text-blue-500 hover:underline">
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

    <?php if (empty($siswas)): ?>
    <div class="bg-white rounded-lg shadow-lg p-6 text-center text-gray-500">
      <p>Tidak ada data siswa. <a href="tambahsiswa" class="text-blue-500 hover:underline">Tambah siswa dulu</a></p>
    </div>
    <?php endif; ?>

  </div>

  <?php include 'includes/footer.php'; ?>

</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
  const keyword = this.value.toLowerCase();
  const cards = document.querySelectorAll('.penilaian-card');

  cards.forEach(card => {
    const text = card.textContent.toLowerCase();
    card.style.display = text.includes(keyword) ? '' : 'none';
  });
});
</script>