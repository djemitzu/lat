<?php
include_once "library.php";
$con = openConnection();
$dk_id = $_POST['dk_id'];
try {
    // Query untuk mengambil data dari tabel dk
    $sql = "SELECT group_id, room_id, tanggal, jenis, keterangan, amount, dk_id FROM dk WHERE dk_id = :dk_id";
    $arr = queryArrayValue($con, $sql, array("dk_id" => $dk_id));
    header("Content-Type: application/json");
    if ($arr) {
        echo json_encode($arr);
    } else {
        throw new Exception("DK ID tidak ditemukan!");
    }
} catch (Exception $e) {
    $dataArr["error"] = $e->getMessage();
    echo json_encode($dataArr);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);