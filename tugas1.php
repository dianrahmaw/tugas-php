<?php
// =====================================================
// Latihan Individu: Tabel Nilai, IPS, dan Batas SKS
// =====================================================

// 1. Array multidimensi berisi minimal 5 mata kuliah
//    (ganti isinya dengan mata kuliah kamu sendiri)
$matkul = [
    ["nama" => "Pemrograman Web",  "sks" => 3, "nilai" => 85],
    ["nama" => "Basis Data",       "sks" => 3, "nilai" => 72],
    ["nama" => "Algoritma",        "sks" => 4, "nilai" => 65],
    ["nama" => "Jaringan Komputer","sks" => 2, "nilai" => 48],
    ["nama" => "Bahasa Inggris",   "sks" => 2, "nilai" => 90],
    ["nama" => "Matematika Diskrit","sks" => 3, "nilai" => 55],
];

// 2. Fungsi helper: nilai angka -> huruf + bobot
//    (rentang nilai di bawah adalah contoh umum; sesuaikan dengan aturan kampus)
function hitungBobot($nilai)
{
    if ($nilai >= 80) {
        return ["huruf" => "A", "bobot" => 4.0];
    } elseif ($nilai >= 70) {
        return ["huruf" => "B", "bobot" => 3.0];
    } elseif ($nilai >= 60) {
        return ["huruf" => "C", "bobot" => 2.0];
    } elseif ($nilai >= 50) {
        return ["huruf" => "D", "bobot" => 1.0];
    } else {
        return ["huruf" => "E", "bobot" => 0.0];
    }
}

// 3. Hitung semua data SEBELUM menampilkan HTML.
//    Hasilnya disimpan di $baris supaya HTML di bawah tinggal menampilkan.
$baris      = [];
$totalMutu  = 0; // jumlah dari (SKS x bobot)
$totalSks   = 0; // jumlah SKS

foreach ($matkul as $mk) {
    $hasil  = hitungBobot($mk["nilai"]);
    $status = ($hasil["bobot"] >= 2.0) ? "LULUS" : "MENGULANG";

    $baris[] = [
        "nama"   => $mk["nama"],
        "sks"    => $mk["sks"],
        "nilai"  => $mk["nilai"],
        "huruf"  => $hasil["huruf"],
        "status" => $status,
    ];

    $totalMutu += $mk["sks"] * $hasil["bobot"];
    $totalSks  += $mk["sks"];
}

// 4. Hitung IPS (cegah pembagian dengan nol)
$ips = ($totalSks > 0) ? $totalMutu / $totalSks : 0;

// 5. Batas maksimal SKS semester depan
$maksSks = ($ips >= 3.00) ? 24 : 20;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Nilai Mahasiswa</title>
    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            max-width: 800px;
            margin: 32px auto;
            padding: 0 16px;
            color: #1f2937;
        }
        h1 { margin-bottom: 4px; }
        .tabel-wrap { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            text-align: left;
        }
        th { background: #e2e8f0; }
        td.tengah, th.tengah { text-align: center; }
        /* Baris merah untuk mata kuliah yang harus mengulang */
        tr.mengulang { background: #fee2e2; color: #991b1b; }
        .ringkasan {
            margin-top: 24px;
            padding: 16px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
        }
        .ringkasan p { margin: 6px 0; }
    </style>
</head>
<body>
    <h1>Daftar Nilai</h1>
    <p>Semester ini</p>

    <div class="tabel-wrap">
        <table>
            <tr>
                <th class="tengah">No</th>
                <th>Mata Kuliah</th>
                <th class="tengah">SKS</th>
                <th class="tengah">Nilai</th>
                <th class="tengah">Huruf</th>
                <th class="tengah">Status</th>
            </tr>

            <?php foreach ($baris as $i => $b): ?>
                <tr class="<?= $b["status"] === "MENGULANG" ? "mengulang" : "" ?>">
                    <td class="tengah"><?= $i + 1 ?></td>
                    <td><?= $b["nama"] ?></td>
                    <td class="tengah"><?= $b["sks"] ?></td>
                    <td class="tengah"><?= $b["nilai"] ?></td>
                    <td class="tengah"><?= $b["huruf"] ?></td>
                    <td class="tengah"><?= $b["status"] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="ringkasan">
        <h2 style="margin-top:0">Ringkasan IPS</h2>
        <p>Total SKS: <strong><?= $totalSks ?></strong></p>
        <p>Total mutu (SKS x bobot): <strong><?= number_format($totalMutu, 2) ?></strong></p>
        <p>IPS: <strong><?= number_format($ips, 2) ?></strong></p>
        <p>
            Batas maksimal SKS semester depan:
            <strong><?= $maksSks ?> SKS</strong>
            (<?= $ips >= 3.00 ? "IPS 3,00 ke atas" : "IPS di bawah 3,00" ?>)
        </p>
    </div>
</body>
</html>