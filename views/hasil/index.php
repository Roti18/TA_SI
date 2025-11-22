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

$title = "Hasil Ranking";

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
    SELECT p.*, s.nama as siswa_nama, s.nis, k.id as kriteria_id, k.nama as kriteria_nama, k.jenis, k.bobot
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

// Hitung Preferensi (Vi) = Sum(Wj * Rij)
$hasilPreferensi = [];
foreach ($siswas as $siswa) {
    $totalPreferensi = 0;
    foreach ($kriterias as $kriteria) {
        if (isset($matrixNormalisasi[$siswa['id']][$kriteria['id']])) {
            $totalPreferensi += $kriteria['bobot'] * $matrixNormalisasi[$siswa['id']][$kriteria['id']];
        }
    }
    $hasilPreferensi[$siswa['id']] = round($totalPreferensi, 4);
}

// Ranking
arsort($hasilPreferensi);
?>

<div class="ml-64 flex min-h-screen bg-gray-100">
  <?php include 'includes/sidebar.php'; ?>
  <?php include "includes/header.php"; ?>

  <div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800 flex items-center">
        <i data-lucide="trophy" class="w-8 h-8 mr-3 text-yellow-500"></i>
        Hasil Ranking Siswa Berprestasi
      </h1>
      <a href="perhitungan"
        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300 flex items-center">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
        Kembali ke Perhitungan
      </a>
    </div>

    <?php include "includes/notification.php"; ?>

    <!-- Tabel Ranking Lengkap -->
    <div class="bg-white rounded-lg shadow-lg p-6">
      <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i data-lucide="list-ordered" class="w-6 h-6 mr-2 text-blue-600"></i>
        Daftar Ranking Lengkap
      </h2>

      <!-- Search Bar -->
      <div class="mb-4">
        <div class="relative">
          <input type="text" id="searchInput" placeholder="Cari siswa..."
            class="w-full pl-10 pr-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i data-lucide="search" class="text-gray-400"></i>
          </div>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
          <thead class="bg-blue-600 text-white">
            <tr>
              <th class="py-3 px-4 text-center text-sm font-semibold border">Ranking</th>
              <th class="py-3 px-4 text-sm font-semibold border">NIS</th>
              <th class="py-3 px-4 text-sm font-semibold border">Nama Siswa</th>
              <th class="py-3 px-4 text-center text-sm font-semibold border">Nilai Preferensi (Vi)</th>
            </tr>
          </thead>
          <tbody class="text-gray-700" id="rankingTable">
            <?php 
            $rank = 1;
            foreach ($hasilPreferensi as $siswa_id => $preferensi): 
              $siswa = array_filter($siswas, function($s) use ($siswa_id) {
                return $s['id'] == $siswa_id;
              });
              $siswa = reset($siswa);
              
              if ($rank == 1) {
                $badgeColor = 'bg-yellow-500';
                $status = '<span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full">🏆 Juara 1</span>';
              } elseif ($rank == 2) {
                $badgeColor = 'bg-gray-400';
                $status = '<span class="bg-gray-100 text-gray-800 text-xs font-bold px-3 py-1 rounded-full">🥈 Juara 2</span>';
              } elseif ($rank == 3) {
                $badgeColor = 'bg-orange-600';
                $status = '<span class="bg-orange-100 text-orange-800 text-xs font-bold px-3 py-1 rounded-full">🥉 Juara 3</span>';
              } else {
                $badgeColor = 'bg-blue-500';
                $status = '<span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">Peserta</span>';
              }
            ?>
            <tr class="hover:bg-gray-50 border-b ranking-row">
              <td class="py-3 px-4 border text-center">
                <span class="<?= $badgeColor ?> text-white text-sm font-bold px-3 py-1 rounded-full">
                  #<?= $rank ?>
                </span>
              </td>
              <td class="py-3 px-4 border text-center">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                  <?= htmlspecialchars($siswa['nis']) ?>
                </span>
              </td>
              <td class="py-3 px-4 border font-semibold text-center"><?= htmlspecialchars($siswa['nama']) ?></td>
              <td class="py-3 px-4 border text-center">
                <span class="bg-green-100 text-green-800 text-sm font-bold px-3 py-1 rounded-full">
                  <?= htmlspecialchars($preferensi) ?>
                </span>
              </td>
            </tr>
            <?php 
            $rank++;
            endforeach; 
            ?>
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
  const rows = document.querySelectorAll('.ranking-row');

  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(keyword) ? '' : 'none';
  });
});
</script>