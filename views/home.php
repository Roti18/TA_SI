<?php require "config/helpers.php" ?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPK SAW — Landing Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

  <!-- Hero Section -->
  <section class="min-h-screen flex items-center justify-center text-white relative">

    <!-- Background gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-700 via-blue-500 to-blue-400 animate-pulse-slow"></div>

    <!-- Pattern -->
    <div class="absolute inset-0 opacity-10"
      style="background-image: radial-gradient(circle,#fff 1px,transparent 1px); background-size: 30px 30px;"></div>

    <!-- Content -->
    <div class="relative z-10 text-center px-6">
      <h1 class="text-5xl font-extrabold drop-shadow-lg mb-4">
        Sistem Pendukung Keputusan<br>
        <span class="text-yellow-300">Metode SAW</span>
      </h1>

      <p class="text-xl opacity-90 max-w-2xl mx-auto">
        Platform modern untuk menentukan siswa berprestasi secara objektif, cepat, dan akurat.
      </p>

      <a href="<?= route('login'); ?>"
        class="mt-8 inline-block bg-white text-blue-700 font-bold px-8 py-3 rounded-full shadow hover:bg-gray-100 transition">
        Mulai Sekarang
      </a>
    </div>
  </section>

  <!-- Fitur -->
  <section class="py-20">
    <h2 class="text-4xl font-bold text-center text-gray-700 mb-12">Fitur Unggulan</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 px-6 md:px-20">

      <!-- Card -->
      <div class="bg-white shadow-xl rounded-xl p-8 transform hover:-translate-y-2 transition group">
        <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mb-4 group-hover:bg-blue-600 transition">
          <!-- Icon -->
          <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition" fill="none"
            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M3 3h18v4H3z"></path>
            <path d="M3 7h18v14H3z"></path>
            <path d="M8 11h8v2H8zM8 15h5v2H8z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-blue-600 mb-2">Data Kriteria</h3>
        <p class="text-gray-600">Mengatur bobot dan parameter seperti raport, sikap, absensi, dan aktivitas siswa.</p>
      </div>

      <!-- Card -->
      <div class="bg-white shadow-xl rounded-xl p-8 transform hover:-translate-y-2 transition group">
        <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center mb-4 group-hover:bg-indigo-600 transition">
          <svg class="w-8 h-8 text-indigo-600 group-hover:text-white transition" fill="none"
            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 6v12"></path>
            <path d="M6 12h12"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-indigo-600 mb-2">Input Penilaian</h3>
        <p class="text-gray-600">Form interaktif untuk memasukkan nilai siswa berdasarkan standar penilaian.</p>
      </div>

      <!-- Card -->
      <div class="bg-white shadow-xl rounded-xl p-8 transform hover:-translate-y-2 transition group">
        <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center mb-4 group-hover:bg-purple-600 transition">
          <svg class="w-8 h-8 text-purple-600 group-hover:text-white transition" fill="none"
            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M5 12h14"></path>
            <path d="M12 5l7 7-7 7"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-purple-600 mb-2">Perhitungan SAW</h3>
        <p class="text-gray-600">Normalisasi otomatis, hasil preferensi, dan ranking terbaik secara instan.</p>
      </div>

    </div>
  </section>

  <!-- Tentang -->
  <section class="py-20 bg-gray-200">
    <div class="max-w-4xl mx-auto text-center px-6">

      <h2 class="text-4xl font-bold text-gray-700 mb-6">Tentang Sistem</h2>

      <p class="text-gray-600 text-lg leading-relaxed">
        Sistem ini dibangun untuk mendukung proses penilaian siswa secara objektif menggunakan metode
        Simple Additive Weighting (SAW). Tanpa perhitungan manual, tanpa kesalahan, dan jauh lebih efisien.
      </p>

      <!-- Box highlight -->
      <div class="mt-10 p-6 rounded-xl bg-white shadow-lg inline-block">
        <span class="text-blue-600 font-bold text-2xl">Akurasi 100%</span>
        <p class="text-gray-600 text-sm">Terbukti melalui uji perbandingan manual vs sistem</p>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="py-20 bg-blue-600 text-center text-white">
    <h2 class="text-3xl font-extrabold mb-4">Siap Menggunakan Sistem?</h2>
    <p class="text-lg mb-6">Percepat penilaian siswa dengan teknologi modern.</p>

    <a href="<?= route('login'); ?>"
      class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold shadow hover:bg-gray-200 transition">
      Login Sekarang
    </a>
  </section>

  <!-- Footer -->
  <footer class="bg-white py-6 text-center text-gray-600 border-t">
    © <?= date("Y") ?> SPK SAW — Siswa Berprestasi
  </footer>

</body>

</html>