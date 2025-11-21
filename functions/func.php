<?php 
function redirect($url){
    echo "<script>window.location.href='$url';</script>";
    exit;
}

function alertRedirect($pesan, $url){
    echo "<script>
            alert('$pesan');
            window.location.href='$url';
          </script>";
    exit;
}

function formatRupiah($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
}

function tanggalInd($tanggal){
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
?>