<?php

$db = mysqli_connect("waytech.kr", "test", "123456", "test");

$mac = $_POST['mac'];

if($db) {
    echo "성공";
} else {
    echo "실패";
}
/*
$stmt = mysqli_prepare($db, 'INSERT INTO pis VALUES(NULL,?,?)');
$stmt->bind_param("si", $mac, $stat);
$result = $stmt->execute();
*/
$result = mysqli_query($db, "SELECT (stat) from pis where mac = '" . $mac . "'");
$row = mysqli_fetch_assoc($result);

if($result === false) {
    echo "fail";
} else {
    echo $row;
}
?>