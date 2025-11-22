<?php
$title = "Tambah Data Siswa";
include_once "D:/Xampp/htdocs/TugasAkhirSI/includes/header.php";
include_once "D:/Xampp/htdocs/TugasAkhirSI/includes/sidebar.php";

// Placeholder for classes dropdown
$classes = [
    ['id_kelas' => 1, 'nama_kelas' => 'XII RPL 1'],
    ['id_kelas' => 2, 'nama_kelas' => 'XII RPL 2'],
    ['id_kelas' => 3, 'nama_kelas' => 'XII TKJ 1'],
    ['id_kelas' => 4, 'nama_kelas' => 'XII TKJ 2'],
];
?>

<div class="flex-1 p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Tambah Siswa Baru</h1>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
            <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="../../functions/func.php?action=create" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NIS -->
                <div class="mb-4">
                    <label for="nis" class="block text-gray-700 font-semibold mb-2">NIS</label>
                    <input type="text" id="nis" name="nis" class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan NIS siswa" required>
                </div>

                <!-- Nama Lengkap -->
                <div class="mb-4">
                    <label for="nama_lengkap" class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama lengkap" required>
                </div>

                <!-- Alamat -->
                <div class="mb-4 md:col-span-2">
                    <label for="alamat" class="block text-gray-700 font-semibold mb-2">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="4" class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan alamat lengkap" required></textarea>
                </div>

                <!-- Kelas -->
                <div class="mb-4">
                    <label for="id_kelas" class="block text-gray-700 font-semibold mb-2">Kelas</label>
                    <select id="id_kelas" name="id_kelas" class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white" required>
                        <option value="" disabled selected>Pilih Kelas</option>
                        <?php foreach($classes as $class): ?>
                            <option value="<?= $class['id_kelas'] ?>"><?= htmlspecialchars($class['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
                    <i data-lucide="save" class="inline-block w-5 h-5 mr-2"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<?php
include_once "D:/Xampp/htdocs/TugasAkhirSI/includes/footer.php";
?>
