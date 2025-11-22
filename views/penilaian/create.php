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

$title = "Tambah Data Penilaian";

// Ambil data siswa
$querySiswa = "SELECT id, nis, nama, kelas FROM siswa ORDER BY nama ASC";
$resultSiswa = $conn->query($querySiswa);
$siswas = $resultSiswa->fetch_all(MYSQLI_ASSOC);

// Ambil data kriteria
$queryKriteria = "SELECT id, kode, nama, bobot, jenis FROM kriteria ORDER BY id ASC";
$resultKriteria = $conn->query($queryKriteria);
$kriterias = $resultKriteria->fetch_all(MYSQLI_ASSOC);

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

$errors = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $penilaian = [
        'siswa_id' => isset($_POST['siswa_id']) ? intval($_POST['siswa_id']) : 0,
        'kriteria_id' => isset($_POST['kriteria_id']) ? intval($_POST['kriteria_id']) : 0,
        'nilai_input' => isset($_POST['nilai_input']) ? trim($_POST['nilai_input']) : '',
        'rating' => isset($_POST['rating']) ? intval($_POST['rating']) : 0
    ];

    // Validasi siswa_id
    if (empty($penilaian['siswa_id'])) {
        $errors['siswa_id'] = 'Pilih siswa terlebih dahulu';
    }

    // Validasi kriteria_id
    if (empty($penilaian['kriteria_id'])) {
        $errors['kriteria_id'] = 'Pilih kriteria terlebih dahulu';
    }

    // Validasi nilai_input
    if (empty($penilaian['nilai_input'])) {
        $errors['nilai_input'] = 'Nilai input wajib diisi';
    }

    // Validasi rating
    if (empty($penilaian['rating'])) {
        $errors['rating'] = 'Rating wajib diisi';
    }

    // Cek duplikasi penilaian
    if (!empty($penilaian['siswa_id']) && !empty($penilaian['kriteria_id'])) {
        $stmtCek = $conn->prepare("SELECT id FROM penilaian WHERE siswa_id = ? AND kriteria_id = ?");
        $stmtCek->bind_param("ii", $penilaian['siswa_id'], $penilaian['kriteria_id']);
        $stmtCek->execute();
        $resultCek = $stmtCek->get_result();
        
        if ($resultCek->num_rows > 0) {
            $errors['duplikasi'] = 'Penilaian untuk siswa dan kriteria ini sudah ada';
        }
        $stmtCek->close();
    }

    // Simpan data jika valid
    if (empty($errors)) {
        $stmt = $conn->prepare("
            INSERT INTO penilaian (siswa_id, kriteria_id, nilai_input, rating) 
            VALUES (?, ?, ?, ?)
        ");
        
        if ($stmt) {
            $stmt->bind_param("iisi", $penilaian['siswa_id'], $penilaian['kriteria_id'], $penilaian['nilai_input'], $penilaian['rating']);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Penilaian berhasil ditambahkan';
                header("Location: penilaian");
                exit;
            } else {
                $errors['database'] = 'Gagal menyimpan data: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors['database'] = 'Error prepare statement: ' . $conn->error;
        }
    }
}
?>

<div class="ml-64 flex min-h-screen bg-gray-100">
  <?php include 'includes/sidebar.php'; ?>
  <?php include "includes/header.php"; ?>

  <div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Tambah Penilaian Baru</h1>
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

    <div class="w-full">
      <!-- Form Input Penilaian -->
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Form Input Penilaian</h2>
        <form method="POST" action="">
          <div class="space-y-6">
            <!-- Dropdown Siswa -->
            <div>
              <label for="siswa_id" class="block text-gray-700 font-semibold mb-2">
                Siswa
                <span class="text-red-500">*</span>
              </label>
              <select id="siswa_id" name="siswa_id"
                class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white <?= isset($errors['siswa_id']) ? 'border-red-500' : '' ?>"
                required>
                <option value="" disabled <?= empty($_POST['siswa_id'] ?? '') ? 'selected' : '' ?>>
                  Pilih Siswa
                </option>
                <?php foreach ($siswas as $siswa): ?>
                <option value="<?= htmlspecialchars($siswa['id']) ?>"
                  <?= (isset($_POST['siswa_id']) && $_POST['siswa_id'] == $siswa['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($siswa['nis']) ?> - <?= htmlspecialchars($siswa['nama']) ?>
                  (<?= htmlspecialchars($siswa['kelas']) ?>)
                </option>
                <?php endforeach; ?>
              </select>
              <?php if (isset($errors['siswa_id'])): ?>
              <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['siswa_id']) ?></p>
              <?php endif; ?>
            </div>

            <!-- Dropdown Kriteria -->
            <div>
              <label for="kriteria_id" class="block text-gray-700 font-semibold mb-2">
                Kriteria
                <span class="text-red-500">*</span>
              </label>
              <select id="kriteria_id" name="kriteria_id"
                class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white <?= isset($errors['kriteria_id']) ? 'border-red-500' : '' ?>"
                required>
                <option value="" disabled <?= empty($_POST['kriteria_id'] ?? '') ? 'selected' : '' ?>>
                  Pilih Kriteria
                </option>
                <?php foreach ($kriterias as $kriteria): ?>
                <option value="<?= htmlspecialchars($kriteria['id']) ?>"
                  <?= (isset($_POST['kriteria_id']) && $_POST['kriteria_id'] == $kriteria['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($kriteria['nama']) ?>
                </option>
                <?php endforeach; ?>
              </select>
              <?php if (isset($errors['kriteria_id'])): ?>
              <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['kriteria_id']) ?></p>
              <?php endif; ?>
            </div>

            <!-- Nilai Input -->
            <div>
              <label for="nilai_input" class="block text-gray-700 font-semibold mb-2">
                Nilai Input
                <span class="text-red-500">*</span>
              </label>
              <input type="text" id="nilai_input" name="nilai_input" placeholder="Masukkan nilai"
                value="<?= htmlspecialchars($_POST['nilai_input'] ?? '') ?>"
                class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($errors['nilai_input']) ? 'border-red-500' : '' ?>"
                required>
              <?php if (isset($errors['nilai_input'])): ?>
              <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['nilai_input']) ?></p>
              <?php endif; ?>
              <p class="text-gray-500 text-xs mt-1">Rating akan otomatis disesuaikan dengan range sub-kriteria</p>
            </div>

            <!-- Rating -->
            <div>
              <label for="rating" class="block text-gray-700 font-semibold mb-2">
                Rating
                <span class="text-red-500">*</span>
              </label>
              <div class="flex items-center gap-4">
                <select id="rating_display_select"
                  class="flex-1 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                  disabled>
                  <option value="" disabled selected>
                    Rating otomatis
                  </option>
                </select>
                <span id="rating_display" class="text-lg font-bold text-blue-600 min-w-fit">-</span>
              </div>
              <!-- Hidden input untuk mengirim rating value -->
              <input type="hidden" id="rating" name="rating" value="">
              <?php if (isset($errors['rating'])): ?>
              <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['rating']) ?></p>
              <?php endif; ?>
              <div id="sub_kriteria_info" class="text-gray-500 text-xs mt-1 p-2 bg-gray-50 rounded"></div>
            </div>
          </div>

          <div class="mt-8 flex justify-end gap-3">
            <a href="penilaian"
              class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
              <i data-lucide="x" class="inline-block w-5 h-5 mr-2"></i>
              Batal
            </a>
            <button type="submit"
              class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
              <i data-lucide="save" class="inline-block w-5 h-5 mr-2"></i>
              Simpan Penilaian
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

function hitungRating() {
  const nilaiInput = document.getElementById('nilai_input').value;
  const kriteriaId = parseInt(document.getElementById('kriteria_id').value);
  const ratingHidden = document.getElementById('rating');
  const ratingDisplay = document.getElementById('rating_display');
  const subKriteriaInfo = document.getElementById('sub_kriteria_info');

  // Reset jika kriteria belum dipilih
  if (!kriteriaId || !nilaiInput) {
    ratingHidden.value = '';
    ratingDisplay.textContent = '-';
    subKriteriaInfo.innerHTML = '';
    return;
  }

  // Ambil sub_kriteria untuk kriteria yang dipilih
  const subKriterias = subKriteriaBykriteria[kriteriaId] || [];

  if (subKriterias.length === 0) {
    ratingHidden.value = '';
    ratingDisplay.textContent = '-';
    subKriteriaInfo.innerHTML = 'Tidak ada sub-kriteria untuk kriteria ini';
    return;
  }

  // Cari rating berdasarkan nilai
  let foundRating = null;
  let foundSub = null;

  for (let sub of subKriterias) {
    const minVal = parseFloat(sub.min_value);
    const maxVal = parseFloat(sub.max_value);

    // Handle nilai numeric
    if (!isNaN(nilaiInput) && nilaiInput !== '') {
      const numValue = parseFloat(nilaiInput);
      if ((minVal === 0 || !isNaN(minVal)) && (maxVal === 0 || !isNaN(maxVal))) {
        if (numValue >= minVal && numValue <= maxVal) {
          foundRating = sub.rating;
          foundSub = sub;
          break;
        }
      }
    }
    // Handle nilai string (A, B, C, D)
    else if (sub.min_value === null || sub.min_value === '') {
      if (sub.nama && sub.nama.trim().toUpperCase() === nilaiInput.trim().toUpperCase()) {
        foundRating = sub.rating;
        foundSub = sub;
        break;
      }
    }
  }

  if (foundRating !== null && foundSub) {
    ratingHidden.value = foundRating;
    ratingDisplay.textContent = foundRating + '★';
    subKriteriaInfo.innerHTML = `<strong>${foundSub.nama}</strong> → Rating: ${foundRating}`;
  } else {
    ratingHidden.value = '';
    ratingDisplay.textContent = '-';
    subKriteriaInfo.innerHTML = 'Nilai tidak sesuai dengan range sub-kriteria';
  }
}

// Event listener
document.getElementById('nilai_input').addEventListener('input', hitungRating);
document.getElementById('kriteria_id').addEventListener('change', hitungRating);

// Jalankan saat halaman dimuat
window.addEventListener('load', hitungRating);
</script>