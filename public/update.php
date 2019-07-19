<?php

$db = mysqli_connect("waytech.kr", "test", "123456", "test");

$myArray = array();

$result1 = mysqli_query($db, "SELECT * from pis where stat = 0");
//$result2 = mysqli_query($db, "UPDATE pis set stat = 1 where stat = 0");

while($row = mysqli_fetch_assoc($result1)){
    array_push($myArray, $row);
}


if($result === false) {
    echo "fail";
} else {
    echo json_encode($myArray);
}
?>