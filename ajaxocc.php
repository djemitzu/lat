<?php
include_once "library.php";
$con = openConnection();
$body = file_get_contents('php://input');
$dataArr = json_decode($body, true);

// Fungsi untuk memformat uang


try {
    $sql = "SELECT rate.rate FROM room JOIN rate ON room.jenis_kamar = rate.jenis_kamar WHERE room.room_id = :room_id";
    $arr = queryArrayValue($con, $sql, $dataArr);
    header("Content-Type: application/json");
    if ($arr) {
        // Format nilai rate sebelum meng-encode ke JSON
        // $arr['rate'] = $arr['rate'];
        echo json_encode($arr);
    } else {
        throw new Exception("Room ID tidak ditemukan!");
    }
} catch (Exception $e) {
    $dataArr["error"] = $e->getMessage();
    echo json_encode($dataArr);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);