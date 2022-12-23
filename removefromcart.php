<!doctype html>
<html>
<?php
// add to cart
    include './Partials/_dbconnect.php';
    $pid=$_GET['pid'];
    $qry="SELECT * from `sale` where `p_id`='$pid' and status='cart'";
    $res=mysqli_query($conn, $qry);
    $r=mysqli_fetch_assoc($res);
    $q=intval($r["quantity"]);
    if(!$q){
        header("Location: Items.php");
        exit();
    }
    $qry="UPDATE  `sale` set `quantity`=`quantity`-1 where `p_id`='$pid' and `status`='cart'";
    $result=mysqli_query($conn, $qry);
    $qry="UPDATE  `item` set `Quantity`=`Quantity`+1 where `P_id`='$pid'";
    $result=mysqli_query($conn, $qry);


    $Query = "SELECT * FROM `sale`";
    $result = mysqli_query($conn, $Query);
    $num = mysqli_num_rows($result);   //to fetch the number of rows in table
            // mysqli_fetch_assoc($res) this function returns the next row in table 
    if($num>0){
        while($row = mysqli_fetch_assoc($result)){ 
            if($row['quantity']==0){
                $qry="DELETE from `sale` where `p_id`='$row[p_id]'";
                $result=mysqli_query($conn, $qry);
                header("Location: Items.php");
                exit();
            }
        }
    }
    header("Location: Items.php");
    exit();
?>
</html>