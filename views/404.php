<?php require "config/helpers.php" ?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 | Halaman Tidak Ditemukan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
  body {
    background-color: #f3f4f6;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    min-height: 100vh;
    margin: 0;
    overflow-x: hidden;
  }

  .eye-container {
    display: flex;
    justify-content: center;
    gap: 15px;
  }

  .eye {
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 50%;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 3px solid #e5e7eb;
  }

  .pupil {
    width: 35px;
    height: 35px;
    background: #1f2937;
    border-radius: 50%;
    position: absolute;
    transform: translate(0, 0);
    transition: transform 0.1s linear;
  }

  .pupil::after {
    content: '';
    width: 10px;
    height: 10px;
    background: white;
    border-radius: 50%;
    position: absolute;
    top: 5px;
    right: 5px;
  }

  .glitch {
    font-weight: 900;
    color: #3b82f6;
    text-shadow: 3px 3px 0 #60a5fa, -2px -2px 0 #93c5fd;
    animation: float 3s ease-in-out infinite;
  }

  @keyframes float {

    0%,
    100% {
      transform: translateY(0);
    }

    50% {
      transform: translateY(-10px);
    }
  }

  .bg-decoration {
    position: fixed;
    border-radius: 50%;
    opacity: 0.1;
    z-index: 0;
  }

  .bg-decoration-1 {
    background: #3b82f6;
    top: -50px;
    left: -50px;
    width: 150px;
    height: 150px;
  }

  .bg-decoration-2 {
    background: #10b981;
    bottom: -50px;
    right: -50px;
    width: 150px;
    height: 150px;
  }

  /* Responsive */
  @media (min-width: 640px) {
    .eye {
      width: 100px;
      height: 100px;
    }

    .pupil {
      width: 40px;
      height: 40px;
    }

    .pupil::after {
      width: 12px;
      height: 12px;
      top: 6px;
      right: 6px;
    }

    .eye-container {
      gap: 20px;
    }

    .bg-decoration-1,
    .bg-decoration-2 {
      width: 200px;
      height: 200px;
    }
  }

  @media (min-width: 768px) {
    .eye {
      width: 120px;
      height: 120px;
    }

    .pupil {
      width: 50px;
      height: 50px;
    }

    .pupil::after {
      width: 15px;
      height: 15px;
      top: 8px;
      right: 8px;
    }

    .eye-container {
      gap: 30px;
    }

    .bg-decoration-1,
    .bg-decoration-2 {
      width: 300px;
      height: 300px;
      top: -100px;
      left: -100px;
    }

    .bg-decoration-2 {
      top: auto;
      left: auto;
      bottom: -100px;
      right: -100px;
    }
  }
  </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
  <!-- Background Decorations -->
  <div class="bg-decoration bg-decoration-1"></div>
  <div class="bg-decoration bg-decoration-2"></div>

  <div class="relative z-10 w-full max-w-lg mx-auto">
    <div class="bg-white p-6 sm:p-8 md:p-12 rounded-2xl md:rounded-3xl shadow-xl text-center">
      <!-- Eyes -->
      <div class="eye-container mb-4 sm:mb-6">
        <div class="eye">
          <div class="pupil"></div>
        </div>
        <div class="eye">
          <div class="pupil"></div>
        </div>
      </div>

      <!-- 404 Text -->
      <h1 class="glitch text-6xl sm:text-7xl md:text-8xl lg:text-9xl mb-2 sm:mb-4">404</h1>

      <!-- Messages -->
      <p class="text-gray-800 text-lg sm:text-xl md:text-2xl font-medium mb-2">
        Oops! Halaman tidak ditemukan
      </p>
      <p class="text-gray-500 text-sm sm:text-base mb-6 sm:mb-8 px-2">
        Halaman yang Anda cari mungkin sudah dipindahkan, dihapus, atau tidak pernah ada.
      </p>

      <!-- Button -->
      <a href="<?= route('dashboard'); ?>"
        class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
        <i data-lucide="home" class="w-5 h-5"></i>
        <span>Kembali ke Dashboard</span>
      </a>

      <!-- Hint -->
      <p class="mt-6 sm:mt-8 text-gray-400 text-xs sm:text-sm flex items-center justify-center gap-1">
        <i data-lucide="mouse-pointer" class="w-4 h-4"></i>
        <span class="hidden sm:inline">Gerakkan kursor Anda dan lihat mata mengikuti!</span>
        <span class="sm:hidden">Sentuh layar dan lihat mata mengikuti!</span>
      </p>
    </div>
  </div>

  <script>
  lucide.createIcons();

  function moveEyes(x, y) {
    const pupils = document.querySelectorAll('.pupil');
    const eyes = document.querySelectorAll('.eye');

    eyes.forEach((eye, index) => {
      const rect = eye.getBoundingClientRect();
      const centerX = rect.left + rect.width / 2;
      const centerY = rect.top + rect.height / 2;

      const deltaX = x - centerX;
      const deltaY = y - centerY;
      const angleRad = Math.atan2(deltaY, deltaX);

      // Responsive radius
      const eyeWidth = rect.width;
      const radius = eyeWidth * 0.2;

      const pupilX = Math.cos(angleRad) * radius;
      const pupilY = Math.sin(angleRad) * radius;

      pupils[index].style.transform = `translate(${pupilX}px, ${pupilY}px)`;
    });
  }

  // Mouse move (desktop)
  document.addEventListener('mousemove', (e) => {
    moveEyes(e.clientX, e.clientY);
  });

  // Touch move (mobile)
  document.addEventListener('touchmove', (e) => {
    const touch = e.touches[0];
    moveEyes(touch.clientX, touch.clientY);
  });

  // Touch start (mobile tap)
  document.addEventListener('touchstart', (e) => {
    const touch = e.touches[0];
    moveEyes(touch.clientX, touch.clientY);
  });
  </script>
</body>

</html>