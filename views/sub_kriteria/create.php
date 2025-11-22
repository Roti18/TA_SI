<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../config/connect.php';
include __DIR__ . '/../../functions/func.php';

$title = "Tambah Sub Kriteria";

$kriteria_id = $_GET['kriteria_id'] ?? null;

if (!$kriteria_id) {
    echo "Kriteria ID tidak valid.";
    exit;
}

// Ambil data kriteria untuk header
$stmt = $conn->prepare("SELECT * FROM kriteria WHERE id = ?");
$stmt->bind_param("i", $kriteria_id);
$stmt->execute();
$kriteria = $stmt->get_result()->fetch_assoc();

if (!$kriteria) {
    echo "Kriteria tidak ditemukan.";
    exit;
}

if (isset($_POST['nama'])) {
    $data = [
        'kriteria_id' => $kriteria_id,
        'nama'        => $_POST['nama'],
        'min_value'   => $_POST['min_value'] !== '' ? $_POST['min_value'] : null,
        'max_value'   => $_POST['max_value'] !== '' ? $_POST['max_value'] : null,
        'rating'      => $_POST['rating']
    ];
    
    createData('sub_kriteria', $data, 'sub-kriteria', 'tambahsub-kriteria?kriteria_id=' . $kriteria_id);
}
?>

<div class="min-h-screen flex bg-gray-100">
  <?php include 'includes/sidebar.php'; ?>
  <?php include "includes/header.php"; ?>

  <div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Tambah Sub Kriteria</h1>
        <p class="text-gray-600 mt-1">
          Untuk kriteria:
          <span
            class="bg-blue-500 text-white px-2 py-1 rounded text-sm"><?= htmlspecialchars($kriteria['kode']) ?></span>
          <?= htmlspecialchars($kriteria['nama']) ?>
        </p>
      </div>
      <a href="sub-kriteria"
        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
        <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i>
        Kembali
      </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
      <form method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Nama -->
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Nama</label>
            <input type="text" name="nama" placeholder="Contoh: 91-100 atau A"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
          </div>
          <!-- Rating -->
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Rating</label>
            <input type="number" name="rating" min="1" max="5" placeholder="1-5"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
          </div>
          <!-- Min Value -->
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Min Value <span
                class="text-gray-400 text-sm">(Opsional)</span></label>
            <input type="number" name="min_value" placeholder="Contoh: 91"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
          </div>
          <!-- Max Value -->
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Max Value <span
                class="text-gray-400 text-sm">(Opsional)</span></label>
            <input type="number" name="max_value" placeholder="Contoh: 100"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
          </div>
        </div>

        <div class="mt-8 flex justify-end">
          <button type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
            <i data-lucide="save" class="inline-block w-5 h-5 mr-2"></i>
            Simpan Data
          </button>
        </div>
      </form>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>
</div>