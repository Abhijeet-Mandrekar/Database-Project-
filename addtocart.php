<!doctype html>
<html>
<?php
// add to cart
    include './Partials/_dbconnect.php';
    $pid=$_GET['pid'];
    $qry="SELECT * from `item` where `P_id`='$pid'";
    $res=mysqli_query($conn, $qry);
    $r=mysqli_fetch_assoc($res);
    $q=intval($r["Quantity"]);
    if(!$q){
        header("Location: Items.php");
        exit();
    }
    $ptbu=intval($r["Price"]);
    $qry="UPDATE  `item` set `Quantity`=`Quantity`-1 where `P_id`='$pid'";
    $result=mysqli_query($conn, $qry);
    $qry="SELECT * from `sale` where `p_id`='$pid' and `status`='cart'";
    $res=mysqli_query($conn,$qry);
    $d=date("Y/m/d");
    if(mysqli_num_rows($res)==0){
        $qry="INSERT into `sale` (`p_id`) select `P_id` from `item` where `P_id`='$pid'";
        $result=mysqli_query($conn, $qry);
        $qry="UPDATE `sale` set `status`='cart' where `p_id`='$pid' and `status` is NULL";
        $result=mysqli_query($conn, $qry);
    }
    
    $r=mysqli_fetch_assoc($res);
    if($r['status']!='sale'){
        $qry="UPDATE  `sale` set `quantity`=`quantity`+1 where `p_id`='$pid'";
        $result=mysqli_query($conn, $qry);
        $qry="UPDATE  `sale` set `s_date`='$d' where `p_id`='$pid'";
        $result=mysqli_query($conn, $qry);
        $qry="UPDATE  `sale` set `amount`=`quantity`*$ptbu where `p_id`='$pid'";
        $result=mysqli_query($conn, $qry);
    }
        if($result){
        echo"Decremented successfully";
    }
    else
        echo"whoopsy";
    header("Location: Items.php");
    exit();
?>
</html>