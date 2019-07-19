<?php

$db = mysqli_connect("waytech.kr", "test", "123456", "test");

$mac = $_POST['mac'];
$stat = $_POST['stat'];

/*
$stmt = mysqli_prepare($db, 'INSERT INTO pis VALUES(NULL,?,?)');
$stmt->bind_param("si", $mac, $stat);
$result = $stmt->execute();
*/
$result = mysqli_query($db, "UPDATE pis set stat = " . $stat . " where mac = '" . $mac . "'");

if($result === false) {
    echo "fail";
} else {
    echo "success";
}
?>