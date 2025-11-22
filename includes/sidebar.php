<?php global $request; ?>
<?php include "config/helpers.php" ?>

<aside class="fixed top-0 left-0 w-64 h-screen overflow-y-auto bg-white shadow-lg border-r">
  <div class="relative p-6 text-2xl font-bold border-b">
    SPK SAW
    <p class="text-sm text-gray-500">Siswa Berprestasi</p>
  </div>

  <nav class="p-4 space-y-2">

    <a href="<?= route('dashboard'); ?>"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'dashboard' ? 'bg-blue-600 text-white' : '' ?>">
      Dashboard
    </a>

    <hr>

    <a href="<?= route('kriteria'); ?>"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'kriteria' ? 'bg-blue-600 text-white' : '' ?>">
      Data Kriteria
    </a>

    <a href="<?= route('sub-kriteria'); ?>"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'sub-kriteria' ? 'bg-blue-600 text-white' : '' ?>">
      Data Sub Kriteria
    </a>

    <a href="<?= route('siswa'); ?>"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'siswa' ? 'bg-blue-600 text-white' : '' ?>">
      Data Alternatif
    </a>

    <a href="<?= route('penilaian'); ?>"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'penilaian' ? 'bg-blue-600 text-white' : '' ?>">
      Data Penilaian
    </a>

    <a href="<?= route('perhitungan'); ?>"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'perhitungan' ? 'bg-blue-600 text-white' : '' ?>">
      Data Perhitungan
    </a>
    <a href="<?= route('hasil-akhir'); ?>"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'hasil-akhir' ? 'bg-blue-600 text-white' : '' ?>">
      Data Hasil Akhir
    </a>

    <a href="<?= route('user-management'); ?>"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'user' ? 'bg-blue-600 text-white' : '' ?>">
      User Management
    </a>
    <hr>
    <a href="<?= route('logout'); ?>" class="block p-3 mt-4 rounded-lg bg-red-100 text-red-600 text-center">
      Logout
    </a>
  </nav>
</aside>