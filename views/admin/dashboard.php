<?php
require_once "config/connect.php";
if (!isset($_SESSION['userdata'])) {
    header("Location: index.php?page=login");
    exit;
}

$students = mysqli_query($conn, "SELECT * FROM siswa");
$kriterias = mysqli_query($conn, "SELECT * FROM kriteria");
$perhitungan = mysqli_query($conn, "SELECT * FROM hasil_akhir");

$rankingTerbaru = mysqli_query($conn, "
    SELECT 
        h.ranking, 
        h.nilai, 
        s.nama,
        s.nis
    FROM hasil_akhir h
    JOIN siswa s ON h.siswa_id = s.id
    ORDER BY h.ranking ASC
    LIMIT 5
");

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
                    <p class="text-4xl font-bold mt-2"><?= mysqli_num_rows($students); ?></p>
                </div>

                <div class="bg-white p-6 shadow rounded-xl border">
                    <h3 class="text-gray-500">Kriteria</h3>
                    <p class="text-4xl font-bold mt-2"><?= mysqli_num_rows($kriterias); ?></p>
                </div>

                <div class="bg-white p-6 shadow rounded-xl border">
                    <h3 class="text-gray-500">Perhitungan Terakhir</h3>
                    <p class="text-xl font-bold mt-2"><?= mysqli_num_rows($perhitungan); ?></p>
                </div>

            </div>

            <h2 class="text-xl font-semibold mt-10 mb-4">Ranking Terbaru</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="py-3 px-4 text-center text-sm font-semibold border">Ranking</th>
                            <th class="py-3 px-4 text-sm font-semibold border">NIS</th>
                            <th class="py-3 px-4 text-sm font-semibold border">Nama Siswa</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold border">Nilai Preferensi (Vi)</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700" id="rankingTable">

                        <?php if (mysqli_num_rows($rankingTerbaru) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($rankingTerbaru)): ?>
                                <?php
                                $badgeColor = 'bg-blue-500';
                                if ($row['ranking'] == 1) $badgeColor = 'bg-yellow-500';
                                elseif ($row['ranking'] == 2) $badgeColor = 'bg-gray-400';
                                elseif ($row['ranking'] == 3) $badgeColor = 'bg-orange-600';
                                ?>

                                <tr class="hover:bg-gray-50 border-b ranking-row">
                                    <td class="py-3 px-4 border text-center">
                                        <span class="<?= $badgeColor ?> text-white text-sm font-bold px-3 py-1 rounded-full">
                                            #<?= $row['ranking'] ?>
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 border text-center">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                                            <?= htmlspecialchars($row['nis']) ?>
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 border font-semibold text-center">
                                        <?= htmlspecialchars($row['nama']) ?>
                                    </td>

                                    <td class="py-3 px-4 border text-center">
                                        <span class="bg-green-100 text-green-800 text-sm font-bold px-3 py-1 rounded-full">
                                            <?= number_format($row['nilai'], 3) ?>
                                        </span>
                                    </td>
                                </tr>

                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="p-3 border text-center text-gray-500">
                                    Belum ada data ranking
                                </td>
                            </tr>
                        <?php endif; ?>

                    </tbody>

                </table>
            </div>


        </div>
        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>

    </div>
</div>