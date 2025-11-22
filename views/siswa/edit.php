<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require __DIR__ . '/../../config/connect.php';
include __DIR__ . '/../../functions/func.php';

$title = "Edit Data Siswa";

$student_id = $_GET['id'] ?? null;
if (!$student_id) {
  echo "ID tidak valid.";
  exit;
}

$stmt = $conn->prepare("SELECT * FROM siswa WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
  echo "Data siswa tidak ditemukan.";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = [
    'nis'   => $_POST['nis'],
    'nama'  => $_POST['nama'],
    'kelas' => $_POST['kelas']
  ];

  $where = [
    'id' => $_POST['id']
  ];

  updateData('siswa', $data, $where, 'siswa', 'edit.php?id=' . $_POST['id']);
}

$classes = ['XI A', 'XI B'];
?>

<div class="ml-64 flex min-h-screen bg-gray-100">
  <?php include 'includes/sidebar.php'; ?>
  <?php include "includes/header.php"; ?>
  <div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Edit Data Siswa</h1>
      <a href="index.php"
        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
        <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i>
        Kembali
      </a>
    </div>
    <div class="bg-white rounded-lg shadow-lg p-6">
      <form method="POST">
        <input type="hidden" name="id" value="<?= $student['id'] ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- NIS -->
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">NIS</label>
            <input type="text" name="nis"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500"
              value="<?= htmlspecialchars($student['nis']) ?>" required>
          </div>
          <!-- Nama -->
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nama</label>
            <input type="text" name="nama"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500"
              value="<?= htmlspecialchars($student['nama']) ?>" required>
          </div>
          <!-- Kelas -->
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Kelas</label>
            <select name="kelas"
              class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 bg-white" required>
              <option disabled>Pilih Kelas</option>
              <?php foreach ($classes as $kelas): ?>
                <option value="<?= $kelas ?>" <?= ($kelas == $student['kelas']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($kelas) ?>
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
      <?php include 'includes/footer.php'; ?>
    </div>
  </div>
</div>