<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include __DIR__ . '/../../functions/func.php';

$title = "Tambah Data Siswa";

if (isset($_POST['nis'])) {
  $data = [
    'nis'   => $_POST['nis'],
    'nama'  => $_POST['nama']
  ];

  createData('siswa', $data, 'siswa', 'tambahsiswa');
}
?>

<div class="ml-64 flex min-h-screen bg-gray-100">
  <?php include 'includes/sidebar.php'; ?>
  <?php include "includes/header.php"; ?>
  <div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Tambah Siswa Baru</h1>
      <a href="index.php"
        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
        <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i>
        Kembali
      </a>
    </div>
    <div class="bg-white rounded-lg shadow-lg p-6">
      <form method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-gray-700 font-semibold mb-2">NIS</label>
            <input type="text" name="nis"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
          </div>
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
            <input type="text" name="nama"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
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
      <?php include 'includes/footer.php'; ?>
    </div>
  </div>
</div>