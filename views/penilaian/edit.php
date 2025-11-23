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

$title = "Update Data Penilaian";

// Ambil siswa_id dari parameter
$siswa_id = isset($_GET['siswa_id']) ? intval($_GET['siswa_id']) : 0;

if (empty($siswa_id)) {
  $_SESSION['error'] = 'ID Siswa tidak valid';
  header("Location: penilaian");
  exit;
}

// Ambil data siswa
$stmtSiswa = $conn->prepare("SELECT id, nis, nama FROM siswa WHERE id = ?");
$stmtSiswa->bind_param("i", $siswa_id);
$stmtSiswa->execute();
$resultSiswa = $stmtSiswa->get_result();
$siswa = $resultSiswa->fetch_assoc();

if (!$siswa) {
  $_SESSION['error'] = 'Data siswa tidak ditemukan';
  header("Location: penilaian");
  exit;
}

// Ambil semua kriteria (4 kriteria)
$queryKriteria = "SELECT id, kode, nama, bobot, jenis FROM kriteria ORDER BY id ASC LIMIT 4";
$resultKriteria = $conn->query($queryKriteria);
$kriterias = $resultKriteria->fetch_all(MYSQLI_ASSOC);

if (count($kriterias) < 4) {
  $_SESSION['error'] = 'Kriteria belum lengkap, minimal 4 kriteria diperlukan';
  header("Location: penilaian");
  exit;
}

// Ambil data sub_kriteria untuk semua kriteria
$querySubKriteria = "SELECT id, kriteria_id, nama, min_value, max_value, rating FROM sub_kriteria ORDER BY kriteria_id ASC, rating ASC";
$resultSubKriteria = $conn->query($querySubKriteria);
$subKriterias = $resultSubKriteria->fetch_all(MYSQLI_ASSOC);

// Grup sub_kriteria berdasarkan kriteria_id
$subKriteriaBykriteria = [];
foreach ($subKriterias as $sub) {
  if (!isset($subKriteriaBykriteria[$sub['kriteria_id']])) {
    $subKriteriaBykriteria[$sub['kriteria_id']] = [];
  }
  $subKriteriaBykriteria[$sub['kriteria_id']][] = $sub;
}

// Ambil penilaian existing untuk siswa ini
$stmtPenilaian = $conn->prepare("SELECT kriteria_id, nilai_input, rating FROM penilaian WHERE siswa_id = ?");
$stmtPenilaian->bind_param("i", $siswa_id);
$stmtPenilaian->execute();
$resultPenilaian = $stmtPenilaian->get_result();
$existingPenilaian = [];
while ($row = $resultPenilaian->fetch_assoc()) {
  $existingPenilaian[$row['kriteria_id']] = $row;
}

$errors = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $conn->begin_transaction();

  try {
    $success = true;

    // Loop untuk 4 kriteria
    foreach ($kriterias as $index => $kriteria) {
      $kriteria_id = $kriteria['id'];
      $nilai_input = isset($_POST["nilai_input_$kriteria_id"]) ? trim($_POST["nilai_input_$kriteria_id"]) : '';
      $rating = isset($_POST["rating_$kriteria_id"]) ? intval($_POST["rating_$kriteria_id"]) : 0;

      // Validasi
      if (empty($nilai_input)) {
        $errors["nilai_input_$kriteria_id"] = "Nilai {$kriteria['nama']} wajib diisi";
        $success = false;
        continue;
      }

      if (empty($rating)) {
        $errors["rating_$kriteria_id"] = "Rating {$kriteria['nama']} tidak valid";
        $success = false;
        continue;
      }

      // Cek apakah penilaian sudah ada
      if (isset($existingPenilaian[$kriteria_id])) {
        // Update
        $stmtUpdate = $conn->prepare("UPDATE penilaian SET nilai_input = ?, rating = ? WHERE siswa_id = ? AND kriteria_id = ?");
        $stmtUpdate->bind_param("siii", $nilai_input, $rating, $siswa_id, $kriteria_id);

        if (!$stmtUpdate->execute()) {
          $errors["database_$kriteria_id"] = "Gagal update {$kriteria['nama']}: " . $stmtUpdate->error;
          $success = false;
        }
        $stmtUpdate->close();
      } else {
        // Insert
        $stmtInsert = $conn->prepare("INSERT INTO penilaian (siswa_id, kriteria_id, nilai_input, rating) VALUES (?, ?, ?, ?)");
        $stmtInsert->bind_param("iisi", $siswa_id, $kriteria_id, $nilai_input, $rating);

        if (!$stmtInsert->execute()) {
          $errors["database_$kriteria_id"] = "Gagal simpan {$kriteria['nama']}: " . $stmtInsert->error;
          $success = false;
        }
        $stmtInsert->close();
      }
    }

    if ($success && empty($errors)) {
      $conn->commit();
      $_SESSION['success'] = 'Penilaian berhasil diperbarui untuk 4 kriteria';
      header("Location: penilaian");
      exit;
    } else {
      $conn->rollback();
    }
  } catch (Exception $e) {
    $conn->rollback();
    $errors['transaction'] = 'Terjadi kesalahan: ' . $e->getMessage();
  }
}
?>

<div class="ml-64 flex min-h-screen bg-gray-100">
  <?php include 'includes/sidebar.php'; ?>
  <?php include "includes/header.php"; ?>

  <div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Update Penilaian - 4 Kriteria</h1>
      <a href="penilaian"
        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
        <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i>
        Kembali
      </a>
    </div>

    <!-- Error Messages -->
    <?php if (!empty($errors)): ?>
      <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <h3 class="text-red-800 font-semibold mb-2">Validasi Gagal</h3>
        <ul class="text-red-700 text-sm space-y-1">
          <?php foreach ($errors as $field => $message): ?>
            <li>• <?= htmlspecialchars($message) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php include "includes/notification.php"; ?>

    <!-- Info Siswa -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 mb-6 text-white">
      <div class="flex items-center gap-4">
        <div class="bg-white rounded-full p-3">
          <i data-lucide="user" class="w-8 h-8 text-blue-600"></i>
        </div>
        <div>
          <h2 class="text-2xl font-bold"><?= htmlspecialchars($siswa['nama']) ?></h2>
          <div class="flex gap-4 mt-2 text-blue-100">
            <span>NIS: <strong><?= htmlspecialchars($siswa['nis']) ?></strong></span>

          </div>
        </div>
      </div>
    </div>

    <div class="w-full">
      <!-- Form Input 4 Kriteria -->
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
          <i data-lucide="clipboard-list" class="w-6 h-6 mr-2"></i>
          Form Penilaian 4 Kriteria
        </h2>

        <form method="POST" action="">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <?php foreach ($kriterias as $index => $kriteria): ?>
              <?php
              $kriteria_id = $kriteria['id'];
              $existing_nilai = isset($existingPenilaian[$kriteria_id]) ? $existingPenilaian[$kriteria_id]['nilai_input'] : '';
              $existing_rating = isset($existingPenilaian[$kriteria_id]) ? $existingPenilaian[$kriteria_id]['rating'] : '';
              ?>

              <!-- Card untuk setiap kriteria -->
              <div
                class="bg-gray-50 rounded-lg p-5 border-2 border-gray-200 hover:border-blue-400 transition duration-200">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-bold text-gray-800">
                    <span class="bg-blue-500 text-white px-2 py-1 rounded text-sm mr-2">
                      <?= htmlspecialchars($kriteria['kode']) ?>
                    </span>
                    <?= htmlspecialchars($kriteria['nama']) ?>
                  </h3>
                  <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">
                    Bobot: <?= htmlspecialchars($kriteria['bobot']) ?>
                  </span>
                </div>

                <!-- Nilai Input -->
                <div class="mb-4">
                  <label for="nilai_input_<?= $kriteria_id ?>" class="block text-gray-700 font-semibold mb-2 text-sm">
                    Nilai Input
                    <span class="text-red-500">*</span>
                  </label>
                  <input type="text" id="nilai_input_<?= $kriteria_id ?>" name="nilai_input_<?= $kriteria_id ?>"
                    placeholder="Masukkan nilai" value="<?= htmlspecialchars($existing_nilai) ?>"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($errors["nilai_input_$kriteria_id"]) ? 'border-red-500' : '' ?>"
                    required data-kriteria-id="<?= $kriteria_id ?>">
                  <?php if (isset($errors["nilai_input_$kriteria_id"])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors["nilai_input_$kriteria_id"]) ?></p>
                  <?php endif; ?>
                </div>

                <!-- Rating Display -->
                <div>
                  <label class="block text-gray-700 font-semibold mb-2 text-sm">
                    Rating Otomatis
                  </label>
                  <div class="flex items-center gap-3">
                    <div class="flex-1 bg-white border rounded-lg px-4 py-2">
                      <span id="rating_text_<?= $kriteria_id ?>" class="text-gray-600 text-sm">
                        <?= !empty($existing_rating) ? "Rating: $existing_rating★" : 'Menunggu input nilai...' ?>
                      </span>
                    </div>
                    <span id="rating_display_<?= $kriteria_id ?>"
                      class="text-2xl font-bold text-blue-600 min-w-[50px] text-center">
                      <?= !empty($existing_rating) ? $existing_rating . '★' : '-' ?>
                    </span>
                  </div>
                  <input type="hidden" id="rating_<?= $kriteria_id ?>" name="rating_<?= $kriteria_id ?>"
                    value="<?= htmlspecialchars($existing_rating) ?>">
                  <div id="sub_kriteria_info_<?= $kriteria_id ?>"
                    class="text-gray-500 text-xs mt-2 p-2 bg-white rounded border"></div>
                </div>
              </div>

            <?php endforeach; ?>

          </div>

          <div class="mt-8 flex justify-end gap-3">
            <a href="penilaian"
              class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300">
              <i data-lucide="x" class="inline-block w-5 h-5 mr-2"></i>
              Batal
            </a>
            <button type="submit"
              class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300">
              <i data-lucide="save" class="inline-block w-5 h-5 mr-2"></i>
              Simpan Semua Penilaian
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>
</div>

<script>
  // Data sub_kriteria dari PHP
  const subKriteriaBykriteria = <?php echo json_encode($subKriteriaBykriteria); ?>;
  const kriteriaIds = <?php echo json_encode(array_column($kriterias, 'id')); ?>;

  function hitungRating(kriteriaId) {
    const nilai = document.getElementById(`nilai_input_${kriteriaId}`).value.trim();
    const rHidden = document.getElementById(`rating_${kriteriaId}`);
    const rDisplay = document.getElementById(`rating_display_${kriteriaId}`);
    const rText = document.getElementById(`rating_text_${kriteriaId}`);

    let rating = 0;

    // ========================
    // C1: NILAI RAPORT (angka)
    // ========================
    if (kriteriaId == 1) {
      let n = parseFloat(nilai);
      if (n >= 91) rating = 4;
      else if (n >= 81) rating = 3;
      else if (n >= 71) rating = 2;
      else rating = 1;
    }

    // ========================
    // C2: SIKAP (huruf)
    // ========================
    else if (kriteriaId == 2) {
      let n = nilai.toUpperCase();
      if (n === "A") rating = 4;
      else if (n === "B") rating = 3;
      else if (n === "C") rating = 2;
      else rating = 1;
    }

    // ========================
    // C3: ABSENSI (persen)
    // ========================
    else if (kriteriaId == 3) {
      let n = parseFloat(nilai);
      if (n == 100) rating = 4;
      else if (n >= 80) rating = 3;
      else if (n >= 75) rating = 2;
      else rating = 1;
    }

    // ========================
    // C4: EKSTRAKURIKULER (huruf)
    // ========================
    else if (kriteriaId == 4) {
      let n = nilai.toUpperCase();
      if (n === "A") rating = 4;
      else if (n === "B") rating = 3;
      else if (n === "C") rating = 2;
      else rating = 1;
    }

    // tampilkan rating
    rHidden.value = rating;
    rDisplay.textContent = rating + "★";
    rText.textContent = "Rating: " + rating + "★";
  }

  // Event listener untuk semua kriteria
  kriteriaIds.forEach(kriteriaId => {
    const inputElement = document.getElementById(`nilai_input_${kriteriaId}`);
    if (inputElement) {
      inputElement.addEventListener('input', function() {
        hitungRating(kriteriaId);
      });
    }
  });

  // Hitung rating saat halaman load
  window.addEventListener('load', function() {
    kriteriaIds.forEach(kriteriaId => {
      hitungRating(kriteriaId);
    });
  });
</script>