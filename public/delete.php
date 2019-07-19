<?php

$db = mysqli_connect("waytech.kr", "test", "123456", "test");

$mac = $_POST['mac'];

/*
$stmt = mysqli_prepare($db, 'INSERT INTO pis VALUES(NULL,?,?)');
$stmt->bind_param("si", $mac, $stat);
$result = $stmt->execute();
*/
$result = mysqli_query($db, "DELETE from pis where mac = '" . $mac . "'");

if($result === false) {
    echo "fail";
} else {
    echo "success";
}
?>