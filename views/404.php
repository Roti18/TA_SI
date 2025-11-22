<?php require "config/helpers.php" ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | Halaman Tersesat</title>
    <style>
        :root {
            --primary-color: #3f51b5;
            /* Biru Indigo */
            --secondary-color: #ff9800;
            /* Oranye */
            --background-color: #1a1a2e;
            /* Biru Gelap/Ungu Tua */
            --text-color: #e0e0e0;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            text-align: center;
            overflow: hidden;
        }

        .container {
            max-width: 90%;
            padding: 20px;
            z-index: 10;
        }

        /* --- Eye Styling --- */
        .eye-container {
            margin-bottom: 30px;
            /* Optional: Memberi sedikit ruang di atas 404 */
        }

        .eye {
            width: 150px;
            height: 150px;
            background: white;
            border-radius: 50%;
            position: relative;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
        }

        .pupil {
            width: 40px;
            height: 40px;
            background: black;
            border-radius: 50%;
            position: absolute;
            /* Pupil bergerak relatif terhadap pusat mata, diatur oleh JS */
            transform: translate(0, 0);
            transition: transform 0.1s linear;
            /* Membuat pergerakan lebih halus */
        }

        /* --- Styling Teks dan Tombol (sama seperti sebelumnya, disingkat) --- */

        .glitch {
            font-size: 8vw;
            /* Dikecilkan agar muat dengan mata */
            margin: 0;
            font-weight: 900;
            position: relative;
            color: var(--primary-color);
            text-shadow:
                0.05em 0 0 #ff00c1,
                -0.03em -0.04em 0 #00ffff,
                0.025em 0.05em 0 #fffc00;
            animation: glitch-anim 2s infinite alternate ease-in-out;
        }

        @keyframes glitch-anim {

            /* ... (keyframes glitch tetap sama) ... */
            0% {
                text-shadow: 0.05em 0 0 #ff00c1, -0.05em -0.025em 0 #00ffff, 0.025em 0.05em 0 #fffc00;
            }

            /* ... (dan seterusnya) ... */
            100% {
                text-shadow: 0.05em 0 0 #ff00c1, -0.05em -0.025em 0 #00ffff, 0.025em 0.05em 0 #fffc00;
            }
        }

        .home-button {
            display: inline-block;
            padding: 12px 30px;
            margin-top: 30px;
            background-color: var(--secondary-color);
            color: var(--background-color);
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.5);
        }

        .home-button:hover {
            background-color: var(--primary-color);
            color: var(--text-color);
            transform: translateY(-3px) scale(1.05);
        }

        .message {
            font-size: 1.2em;
            font-weight: 300;
            margin-bottom: 20px;
        }

        .hint {
            margin-top: 20px;
            opacity: 0.5;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="eye-container">
            <div class="eye">
                <div class="pupil"></div>
            </div>
        </div>

        <h1 class="glitch" data-text="404">404</h1>

        <p class="message">
            Ups! Saya melihat Anda. Halaman yang Anda cari tidak ada.
        </p>

        <a href="<?= route('dashboard'); ?>" class="home-button">
            ← Kembali ke Beranda
        </a>

        <p class="hint">
            Gerakkan kursor Anda!
        </p>
    </div>
    <script>
        document.addEventListener('mousemove', (e) => {
            // 1. Ambil elemen pupil (bagian yang bergerak)
            const pupil = document.querySelector('.pupil');
            // Ambil elemen mata (tempat pupil bergerak)
            const eye = document.querySelector('.eye');

            // 2. Dapatkan posisi tengah mata
            // getBoundingClientRect memberikan koordinat relatif terhadap viewport
            const rect = eye.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;

            // 3. Hitung sudut (angle) kursor dari pusat mata
            // Menggunakan Atan2 (Arc Tangent 2) untuk mendapatkan sudut dalam radian
            // Parameter: (y_cursor - y_center), (x_cursor - x_center)
            const deltaX = e.clientX - centerX;
            const deltaY = e.clientY - centerY;
            const angleRad = Math.atan2(deltaY, deltaX);

            // 4. Konversi radian ke derajat (optional, tapi kadang lebih intuitif)
            // const angleDeg = angleRad * (180 / Math.PI); 

            // 5. Hitung posisi pupil berdasarkan sudut (agar pupil bergerak di sepanjang lingkaran kecil di dalam mata)
            // Kita batasi radius pergerakan (misal 20px) agar pupil tetap di dalam bola mata.
            const radius = 20;
            const pupilX = Math.cos(angleRad) * radius;
            const pupilY = Math.sin(angleRad) * radius;

            // 6. Terapkan transformasi ke pupil
            pupil.style.transform = `translate(${pupilX}px, ${pupilY}px)`;
        });
    </script>
</body>

</html>