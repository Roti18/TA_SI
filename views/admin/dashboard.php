<?php
if (!isset($_SESSION['userdata'])) {
    header("Location: index.php?page=login");
    exit;
}

global $request;
?>

<div class="ml-64 flex min-h-screen bg-gray-100">

  <!-- Sidebar -->
  <?php include 'includes/sidebar.php'; ?>

  <div class="flex-1 flex flex-col">
    <!-- Header -->
    <?php include "includes/header.php"; ?>

    <div class="p-8">

      <h2 class="text-2xl font-bold mb-6">Ringkasan Sistem</h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-6 shadow rounded-xl border">
          <h3 class="text-gray-500">Total Siswa</h3>
          <p class="text-4xl font-bold mt-2">120</p>
        </div>

        <div class="bg-white p-6 shadow rounded-xl border">
          <h3 class="text-gray-500">Kriteria</h3>
          <p class="text-4xl font-bold mt-2">4</p>
        </div>

        <div class="bg-white p-6 shadow rounded-xl border">
          <h3 class="text-gray-500">Perhitungan Terakhir</h3>
          <p class="text-xl font-bold mt-2">Faeza (1.00)</p>
        </div>

      </div>

      <h2 class="text-xl font-semibold mt-10 mb-4">Ranking Terbaru</h2>

      <div class="bg-white p-6 shadow rounded-xl border">
        <table class="w-full border">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-3 border">Rank</th>
              <th class="p-3 border">Nama</th>
              <th class="p-3 border">Nilai</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="p-3 border">1</td>
              <td class="p-3 border">Faeza</td>
              <td class="p-3 border">1.00</td>
            </tr>
            <tr>
              <td class="p-3 border">2</td>
              <td class="p-3 border">Sinta</td>
              <td class="p-3 border">0.90</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

  </div>
</div>