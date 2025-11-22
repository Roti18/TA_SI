<aside class="w-64 bg-white shadow-lg border-r min-h-screen">
  <div class="p-6 text-2xl font-bold border-b">
    SPK SAW
    <p class="text-sm text-gray-500">Siswa Berprestasi</p>
  </div>

  <nav class="p-4 space-y-2">

    <a href="index.php?page=dashboard"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'dashboard' ? 'bg-blue-600 text-white' : '' ?>">
      Dashboard
    </a>

    <hr>

    <a href="kriteria"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'kriteria' ? 'bg-blue-600 text-white' : '' ?>">
      Data Kriteria
    </a>

    <a href="sub-kriteria"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'sub-kriteria' ? 'bg-blue-600 text-white' : '' ?>">
      Data Sub Kriteria
    </a>

    <a href="siswa"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'alternatif' ? 'bg-blue-600 text-white' : '' ?>">
      Data Alternatif
    </a>

    <a href="index.php?page=perhitungan"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'penilaian' ? 'bg-blue-600 text-white' : '' ?>">
      Data Penilaian
    </a>

    <a href="index.php?page=hasil-akhir"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'perhitungan' ? 'bg-blue-600 text-white' : '' ?>">
      Data Perhitungan
    </a>
    <a href="index.php?page=hasil-akhir"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'hasil-akhir' ? 'bg-blue-600 text-white' : '' ?>">
      Data Hasil Akhir
    </a>

    <a href="index.php?page=user"
      class="block p-3 rounded-lg hover:bg-blue-100 <?= $request == 'user' ? 'bg-blue-600 text-white' : '' ?>">
      User Management
    </a>
    <hr>
    <a href="logout.php" class="block p-3 mt-4 rounded-lg bg-red-100 text-red-600 text-center">
      Logout
    </a>
  </nav>
</aside>