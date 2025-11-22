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

if (!isset($_SESSION['userdata'])) {
    header("Location: index.php?page=login");
    exit;
}

$title = "Data Perhitungan";

// Ambil data kriteria
$queryKriteria = "SELECT * FROM kriteria ORDER BY id ASC";
$resultKriteria = $conn->query($queryKriteria);
$kriterias = $resultKriteria->fetch_all(MYSQLI_ASSOC);

// Ambil data siswa
$querySiswa = "SELECT * FROM siswa ORDER BY nama ASC";
$resultSiswa = $conn->query($querySiswa);
$siswas = $resultSiswa->fetch_all(MYSQLI_ASSOC);

// Ambil semua data penilaian
$queryPenilaian = "
    SELECT p.*, s.nama as siswa_nama, k.id as kriteria_id, k.nama as kriteria_nama, k.jenis, k.bobot
    FROM penilaian p
    JOIN siswa s ON p.siswa_id = s.id
    JOIN kriteria k ON p.kriteria_id = k.id
    ORDER BY s.nama ASC, k.id ASC
";
$resultPenilaian = $conn->query($queryPenilaian);
$penilaians = $resultPenilaian->fetch_all(MYSQLI_ASSOC);

// Organisir data penilaian per siswa dan kriteria
$matrixPenilaian = [];
foreach ($penilaians as $p) {
    $matrixPenilaian[$p['siswa_id']][$p['kriteria_id']] = $p['rating'];
}

// Hitung nilai max dan min untuk setiap kriteria
$maxMinKriteria = [];
foreach ($kriterias as $k) {
    $ratings = array_column(
        array_filter($penilaians, function($p) use ($k) {
            return $p['kriteria_id'] == $k['id'];
        }),
        'rating'
    );
    
    if (!empty($ratings)) {
        $maxMinKriteria[$k['id']] = [
            'max' => max($ratings),
            'min' => min($ratings)
        ];
    }
}

// Normalisasi Matrix (X)
$matrixNormalisasi = [];
foreach ($siswas as $siswa) {
    foreach ($kriterias as $kriteria) {
        if (isset($matrixPenilaian[$siswa['id']][$kriteria['id']])) {
            $rating = $matrixPenilaian[$siswa['id']][$kriteria['id']];
            
            // Benefit: Xij / Max
            // Cost: Min / Xij
            if ($kriteria['jenis'] == 'benefit') {
                $normalized = $rating / $maxMinKriteria[$kriteria['id']]['max'];
            } else {
                $normalized = $maxMinKriteria[$kriteria['id']]['min'] / $rating;
            }
            
            $matrixNormalisasi[$siswa['id']][$kriteria['id']] = round($normalized, 4);
        }
    }
}
?>

<div class="ml-64 flex min-h-screen bg-gray-100">
  <?php include 'includes/sidebar.php'; ?>
  <?php include "includes/header.php"; ?>

  <div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Data Perhitungan</h1>
      <a href="hasil"
        class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300 flex items-center">
        <i data-lucide="trophy" class="w-5 h-5 mr-2"></i>
        Lihat Ranking
      </a>
    </div>

    <?php include "includes/notification.php"; ?>

    <!-- Bobot Preferensi (W) -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
      <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i data-lucide="sliders" class="w-6 h-6 mr-2 text-blue-600"></i>
        Bobot Preferensi (W)
      </h2>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
          <thead class="bg-blue-600 text-white">
            <tr>
              <?php foreach ($kriterias as $k): ?>
              <th class="py-3 px-4 text-center text-sm font-semibold border">
                <?= htmlspecialchars($k['nama']) ?> (<?= htmlspecialchars($k['jenis']) ?>)
              </th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <tr class="hover:bg-gray-50">
              <?php foreach ($kriterias as $k): ?>
              <td class="py-3 px-4 text-center border font-semibold">
                <?= htmlspecialchars($k['bobot']) ?>
              </td>
              <?php endforeach; ?>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Matriks Keputusan (X) -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
      <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i data-lucide="grid" class="w-6 h-6 mr-2 text-purple-600"></i>
        Matriks Keputusan (X)
      </h2>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
          <thead class="bg-blue-600 text-white">
            <tr>
              <th class="py-3 px-4 text-left text-sm font-semibold border">No</th>
              <th class="py-3 px-4 text-left text-sm font-semibold border">Nama Siswa</th>
              <?php foreach ($kriterias as $k): ?>
              <th class="py-3 px-4 text-center text-sm font-semibold border">
                <?= htmlspecialchars($k['nama']) ?>
              </th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php $no = 1; foreach ($siswas as $siswa): ?>
            <tr class="hover:bg-gray-50 border-b">
              <td class="py-2 px-4 border text-center"><?= $no++ ?></td>
              <td class="py-2 px-4 border font-medium"><?= htmlspecialchars($siswa['nama']) ?></td>
              <?php foreach ($kriterias as $k): ?>
              <td class="py-2 px-4 border text-center">
                <?= isset($matrixPenilaian[$siswa['id']][$k['id']]) 
                    ? htmlspecialchars($matrixPenilaian[$siswa['id']][$k['id']]) 
                    : '-' ?>
              </td>
              <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Matriks Normalisasi (R) -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
      <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i data-lucide="calculator" class="w-6 h-6 mr-2 text-green-600"></i>
        Matriks Normalisasi (R)
      </h2>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
          <thead class="bg-blue-600 text-white">
            <tr>
              <th class="py-3 px-4 text-left text-sm font-semibold border">No</th>
              <th class="py-3 px-4 text-left text-sm font-semibold border">Nama Siswa</th>
              <?php foreach ($kriterias as $k): ?>
              <th class="py-3 px-4 text-center text-sm font-semibold border">
                <?= htmlspecialchars($k['nama']) ?>
              </th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php $no = 1; foreach ($siswas as $siswa): ?>
            <tr class="hover:bg-gray-50 border-b">
              <td class="py-2 px-4 border text-center"><?= $no++ ?></td>
              <td class="py-2 px-4 border font-medium"><?= htmlspecialchars($siswa['nama']) ?></td>
              <?php foreach ($kriterias as $k): ?>
              <td class="py-2 px-4 border text-center">
                <?= isset($matrixNormalisasi[$siswa['id']][$k['id']]) 
                    ? htmlspecialchars($matrixNormalisasi[$siswa['id']][$k['id']]) 
                    : '-' ?>
              </td>
              <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <?php include 'includes/footer.php'; ?>
</div>