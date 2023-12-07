<?php
include "library.php";

if (isset($_POST['groupid'])) {
    $groupid = $_POST['groupid'];

    $con = openConnection();
    $sql = "SELECT group_id FROM `occupied` WHERE group_id = :groupid";
    $stmt = $con->prepare($sql);
    $stmt->execute(['groupid' => $groupid]);

    if ($stmt->rowCount() > 0) {
        echo 1;  // groupid found
    } else {
        echo 0;  // groupid not found
    }
}