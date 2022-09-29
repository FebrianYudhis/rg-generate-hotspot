<?php

use AnourValar\Office\Format;
use AnourValar\Office\SheetsService;

require 'vendor/autoload.php';

if (isset($_POST['format'])) {
    $randomString = substr(md5(mt_rand()), 0, 7);

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

    $data = explode("\n", file_get_contents($path_filename_ext));

    $hitungBaris = 0;
    $kotimHitung = 0;
    $seruyanHitung = 0;
    $katinganHitung = 0;

    if ($_POST['format'] == "Lama") {
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

        $hasil = ['kotim' => @$kotim, 'katingan' => @$katingan, 'seruyan' => @$seruyan];

        $service = new SheetsService();
        $template =  $service->generate('Template.xlsx', $hasil);
    } elseif ($_POST['format'] == "Baru") {
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

        $hasil = ['kotim' => @$kotim, 'katingan' => @$katingan, 'seruyan' => @$seruyan];

        $service = new SheetsService();
        $template =  $service->generate('Template2.xlsx', $hasil);
    }

    $template->saveAs('download/' . $randomString . '.xlsx', Format::Xlsx);
    $lokasi = 'download/' . $randomString . '.xlsx';
    header("Location: $lokasi");
} else {
    header("Location: index.php");
}
