<?php
include './Partials/_dbconnect.php';
$pid=$_GET['pid'];
$qry="UPDATE `item` set `Quantity`=`Quantity`+ 1 where `P_id`='$pid'";
$result=mysqli_query($conn, $qry);
header("Location: Items.php");
exit();