<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include __DIR__ . '/../../functions/func.php';

$title = "Tambah Data Kriteria";

$jenisList = ['benefit', 'cost'];

if (isset($_POST['kode'])) {
  $data = [
    'kode'  => $_POST['kode'],
    'nama'  => $_POST['nama'],
    'bobot' => $_POST['bobot'],
    'jenis' => $_POST['jenis']
  ];

  createData('kriteria', $data, 'kriteria', 'tambahkriteria');
}
?>

<div class="ml-64 flex min-h-screen bg-gray-100">
  <?php include 'includes/sidebar.php'; ?>
  <?php include "includes/header.php"; ?>

  <div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Tambah Kriteria Baru</h1>
      <a href="kriteria"
        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
        <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i>
        Kembali
      </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
      <form method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Kode -->
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Kode</label>
            <input type="text" name="kode" placeholder="Contoh: C1, C2, C3"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
          </div>
          <!-- Nama -->
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Nama Kriteria</label>
            <input type="text" name="nama" placeholder="Contoh: Rata-rata Raport"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
          </div>
          <!-- Bobot -->
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Bobot</label>
            <input type="number" name="bobot" step="0.01" min="0" max="1" placeholder="Contoh: 0.20"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
          </div>
          <!-- Jenis -->
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Jenis</label>
            <select name="jenis"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 bg-white" required>
              <option value="" disabled selected>Pilih Jenis</option>
              <?php foreach ($jenisList as $jenis): ?>
                <option value="<?= htmlspecialchars($jenis) ?>"><?= ucfirst($jenis) ?></option>
              <?php endforeach; ?>
            </select>
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