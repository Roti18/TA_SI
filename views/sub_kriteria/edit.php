<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../config/connect.php';
include __DIR__ . '/../../functions/func.php';

$title = "Edit Sub Kriteria";

$sub_id = $_GET['id'] ?? null;

if (!$sub_id) {
    echo "ID tidak valid.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM sub_kriteria WHERE id = ?");
$stmt->bind_param("i", $sub_id);
$stmt->execute();
$sub = $stmt->get_result()->fetch_assoc();

if (!$sub) {
    echo "Sub kriteria tidak ditemukan.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM kriteria WHERE id = ?");
$stmt->bind_param("i", $sub['kriteria_id']);
$stmt->execute();
$kriteria = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nama'      => $_POST['nama'],
        'min_value' => $_POST['min_value'] !== '' ? $_POST['min_value'] : null,
        'max_value' => $_POST['max_value'] !== '' ? $_POST['max_value'] : null,
        'rating'    => $_POST['rating']
    ];
    
    $where = ['id' => $_POST['id']];
    
    updateData('sub_kriteria', $data, $where, 'sub-kriteria', 'updatesub-kriteria?id=' . $_POST['id']);
}
?>

<div class="min-h-screen flex bg-gray-100">
  <?php include 'includes/sidebar.php'; ?>
  <?php include "includes/header.php"; ?>

  <div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Edit Sub Kriteria</h1>
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
        <input type="hidden" name="id" value="<?= $sub['id'] ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Nama -->
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Nama</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($sub['nama']) ?>"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
          </div>
          <!-- Rating -->
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Rating</label>
            <input type="number" name="rating" min="1" max="5" value="<?= htmlspecialchars($sub['rating']) ?>"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
          </div>
          <!-- Min Value -->
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Min Value <span
                class="text-gray-400 text-sm">(Opsional)</span></label>
            <input type="number" name="min_value"
              value="<?= $sub['min_value'] !== null ? htmlspecialchars($sub['min_value']) : '' ?>"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
          </div>
          <!-- Max Value -->
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Max Value <span
                class="text-gray-400 text-sm">(Opsional)</span></label>
            <input type="number" name="max_value"
              value="<?= $sub['max_value'] !== null ? htmlspecialchars($sub['max_value']) : '' ?>"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
          </div>
        </div>

        <div class="mt-8 flex justify-end">
          <button type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
            <i data-lucide="save" class="inline-block w-5 h-5 mr-2"></i>
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>
</div>