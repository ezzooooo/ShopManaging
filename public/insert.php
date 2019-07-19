<?php

$db = mysqli_connect("waytech.kr", "test", "123456", "test");

$mac = $_POST['mac'];
$stat = $_POST['stat'];

if($db) {
    echo "성공";
} else {
    echo "실패";
}

$query = "INSERT INTO pis (mac, stat) VALUES ('" . $mac . "', $stat)";
mysqli_query($db, $query);

if($result === false) {
    echo "fail";
} else {
    echo "success";
}
?>
