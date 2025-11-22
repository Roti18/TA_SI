<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require __DIR__ . '/../../config/connect.php';
include __DIR__ . '/../../functions/func.php';

$title = "Edit Data Kriteria";

$kriteria_id = $_GET['id'] ?? null;
if (!$kriteria_id) {
  echo "ID tidak valid.";
  exit;
}

$stmt = $conn->prepare("SELECT * FROM kriteria WHERE id = ?");
$stmt->bind_param("i", $kriteria_id);
$stmt->execute();
$result = $stmt->get_result();
$kriteria = $result->fetch_assoc();

if (!$kriteria) {
  echo "Data kriteria tidak ditemukan.";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = [
    'kode'  => $_POST['kode'],
    'nama'  => $_POST['nama'],
    'bobot' => $_POST['bobot'],
    'jenis' => $_POST['jenis']
  ];

  $where = [
    'id' => $_POST['id']
  ];

  updateData('kriteria', $data, $where, 'kriteria', 'updatekriteria?id=' . $_POST['id']);
}

$jenisList = ['benefit', 'cost'];
?>

<div class="ml-64 flex min-h-screen bg-gray-100">
  <?php include 'includes/sidebar.php'; ?>
  <?php include "includes/header.php"; ?>

  <div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Edit Data Kriteria</h1>
      <a href="kriteria"
        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
        <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i>
        Kembali
      </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
      <form method="POST">
        <input type="hidden" name="id" value="<?= $kriteria['id'] ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Kode -->
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Kode</label>
            <input type="text" name="kode"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500"
              value="<?= htmlspecialchars($kriteria['kode']) ?>" required>
          </div>
          <!-- Nama -->
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nama</label>
            <input type="text" name="nama"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500"
              value="<?= htmlspecialchars($kriteria['nama']) ?>" required>
          </div>
          <!-- Bobot -->
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Bobot</label>
            <input type="number" name="bobot" step="0.01" min="0" max="1"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500"
              value="<?= htmlspecialchars($kriteria['bobot']) ?>" required>
          </div>
          <!-- Jenis -->
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Jenis</label>
            <select name="jenis"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 bg-white" required>
              <option disabled>Pilih Jenis</option>
              <?php foreach ($jenisList as $jenis): ?>
                <option value="<?= $jenis ?>" <?= ($jenis == $kriteria['jenis']) ? 'selected' : '' ?>>
                  <?= ucfirst($jenis) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <!-- Submit -->
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