<?php

// Gunakan Beberapa File Yang Diperlukan
use AnourValar\Office\Format;
use AnourValar\Office\SheetsService;

require 'vendor/autoload.php';

// Jika Formatnya Sudah Dipilih Pada Menu Sebelumnya, Maka Jalankan Kode Berikut
if (isset($_POST['format'])) {

    // Menggenerate Random String
    $randomString = substr(md5(mt_rand()), 0, 7);

    // Upload File Yang Akan DiEksekusi
    if ($_FILES['file']['name'] != "") {
        $target_dir = "upload/";
        $file = $_FILES['file']['name'];
        $path = pathinfo($file);
        $filename = $randomString;
        $ext = $path['extension'];
        $temp_name = $_FILES['file']['tmp_name'];
        $path_filename_ext = $target_dir . $filename . "." . $ext;

        if (file_exists($path_filename_ext)) {
            echo "Gagal Upload File, File Sudah Ada";
        } else {
            move_uploaded_file($temp_name, $path_filename_ext);
        }
    }

    // Baca File Yang Sudah Di Upload
    $data = explode("\n", file_get_contents($path_filename_ext));

    // Inisialisasi Angka Untuk Perulangan
    $hitungBaris = 0;
    $kotimHitung = 0;
    $seruyanHitung = 0;
    $katinganHitung = 0;

    // Jika Format Pada Menu Sebelumnya Memilih Format Lama, Maka Jalankan Kode Berikut
    if ($_POST['format'] == "Lama") {

        // Pecah Datanya Berdasarkan Tab Delimiter
        foreach ($data as $baris) {
            $dataPecah[$hitungBaris] = explode("\t", $baris);
            $dataHasilPecah[$hitungBaris] = [
                'bujur' => @$dataPecah[$hitungBaris][0],
                'lintang' => @$dataPecah[$hitungBaris][1],
                'kepercayaan' => @$dataPecah[$hitungBaris][2],
                'region' => @$dataPecah[$hitungBaris][3],
                'provinsi' => @$dataPecah[$hitungBaris][4],
                'kabupaten' => @$dataPecah[$hitungBaris][5],
                'kecamatan' => @$dataPecah[$hitungBaris][6],
                'satelit' => @$dataPecah[$hitungBaris][7],
                'tanggal' => @$dataPecah[$hitungBaris][8],
                'waktu' => @$dataPecah[$hitungBaris][9],
            ];
            $hitungBaris++;
        }

        // Filter Masing-Masing Datanya
        foreach (array_slice($dataHasilPecah, 1) as $item) {
            if (trim((string) @$item['kabupaten']) == 'KAB. KOTAWARINGIN TIMUR') {
                $kotim[$kotimHitung] = $item;
                $kotimHitung++;
            } elseif ((trim((string) @$item['kabupaten']) == 'KAB. KATINGAN') or (trim((string) @$item['kabupaten']) == 'KATINGAN')) {
                $katingan[$katinganHitung] = $item;
                $katinganHitung++;
            } elseif ((trim((string) @$item['kabupaten']) == 'KAB. SERUYAN') or (trim((string) @$item['kabupaten']) == 'SERUYAN')) {
                $seruyan[$seruyanHitung] = $item;
                $seruyanHitung++;
            }
        }

        // Satukan Datanya
        $hasil = ['kotim' => @$kotim, 'katingan' => @$katingan, 'seruyan' => @$seruyan];

        // Generate Datanya Sesuai File "Template.xlsx"
        $service = new SheetsService();
        $template =  $service->generate('Template.xlsx', $hasil);


        // Jika Format Pada Menu Sebelumnya Memilih Format Baru, Maka Jalankan Kode Berikut
    } elseif ($_POST['format'] == "Baru") {

        // Pecah Datanya Berdasarkan Coma Seperated Value
        foreach ($data as $baris) {
            $dataPecah[$hitungBaris] = explode(",", $baris);
            $dataHasilPecah[$hitungBaris] = [
                'id' => @$dataPecah[$hitungBaris][0],
                'tanggal' => @$dataPecah[$hitungBaris][1],
                'waktu' => @$dataPecah[$hitungBaris][2],
                'lintang' => @$dataPecah[$hitungBaris][3],
                'bujur' => @$dataPecah[$hitungBaris][4],
                'tingkat_kepercayaan' => @$dataPecah[$hitungBaris][5],
                'satelit' => @$dataPecah[$hitungBaris][6],
                'radius_kemungkinan' => @$dataPecah[$hitungBaris][7],
                'kecamatan' => str_replace('"', '', @$dataPecah[$hitungBaris][8]),
                'kabupaten' => str_replace('"', '', @$dataPecah[$hitungBaris][9]),
                'provinsi' => str_replace('"', '', @$dataPecah[$hitungBaris][10]),
                'tipe' => @$dataPecah[$hitungBaris][11],
            ];
            $hitungBaris++;
        }

        // Filter Masing-Masing Datanya
        foreach (array_slice($dataHasilPecah, 1) as $item) {
            if (trim((string) @$item['kabupaten']) == 'Waringin Timur') {
                $kotim[$kotimHitung] = $item;
                $kotimHitung++;
            } elseif (trim((string) @$item['kabupaten']) == 'Katingan') {
                $katingan[$katinganHitung] = $item;
                $katinganHitung++;
            } elseif (trim((string) @$item['kabupaten']) == 'Seruyan') {
                $seruyan[$seruyanHitung] = $item;
                $seruyanHitung++;
            }
        }

        // Satukan Datanya
        $hasil = ['kotim' => @$kotim, 'katingan' => @$katingan, 'seruyan' => @$seruyan];

        // Generate Datanya Sesuai File "Template2.xlsx"
        $service = new SheetsService();
        $template =  $service->generate('Template2.xlsx', $hasil);
    }

    // Simpan dan Download Data Yang Sudah Di Generate
    $template->saveAs('download/' . $randomString . '.xlsx', Format::Xlsx);
    $lokasi = 'download/' . $randomString . '.xlsx';
    header("Location: $lokasi");


    // Jika Formatnya Belum Dipilih Pada Menu Sebelumnya, Maka Jalankan Kode Berikut
} else {
    header("Location: index.php");
}
