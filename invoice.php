<?php
  session_start();
  if(!isset($_SESSION['loggedin'])){
    header("location: ./login_page.php");
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./CSS/invoice-styling.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Finalize Sale</title>
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />

  <!--JAVASCRIPT-->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
        function displayBlock() {
            document.getElementById('Box').style.display = "block";
        }

            function displayNone() {
            document.getElementById('Box').style.display = "none";
            document.getElementById('cv').style.display = "block";
        }

            function check() {
            document.getElementById('cv').style.display = "none";
            confirm('Submitting the Form, This will create a record');
        }
        // function phonenumber(inputtxt){
        //     var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
        //     if((inputtxt.value.match(phoneno))){
        //         return true;
        //     }
        //     else{
        //         alert("Please enter a valid number!");
        //         return false;
        //     }
        // }
        function vanish(){
            document.getElementById("cstmr").style.display="none"
        }
    </script>   

</head>

<body>

<?php
$cid=0;
$discount=0;
$flag=1;
include './Partials/_dbconnect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if(isset($_POST['cstmrveri'])){
        $num = $_POST["number"];
        $query="SELECT * from `customer` where `contact`='$num'";
        $res=mysqli_query($conn, $query);
        $row=mysqli_num_rows($res);
        if($row!=0){
          $cstmr=mysqli_fetch_assoc($res);
          $cid=$cstmr["c_id"];
          $query="UPDATE  `customer` set `discoins`=`discoins`+50 where `c_id`='$cid'";
          $res=mysqli_query($conn, $query);
          if($cstmr["discoins"]>=1000){
            $discount=1;
          }
          if($cstmr["discoins"]>=2000){
            $discount=2;
          }
          if($cstmr["discoins"]>=3000){
            $discount=3;
          }
        }
      }
      if(isset($_POST['customer'])){
        $name = $_POST["name"];
        $contact = $_POST["phone"];
        $email = $_POST["email"];
        $address = $_POST["addr"];
        $sql = "INSERT INTO `customer` ( `name`, `contact`, `email`, `address`, `discoins`) VALUES ( '$name', '$contact', '$email', '$address', '0')";
        $res = mysqli_query($conn, $sql);
      }
      $flag=0;
    }
  ?>

<?php

?>

<div id="Box">
  <div class="New-Box">
    <h2 style="text-align:center;"><span onclick="displayNone()"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" style="cursor:pointer;" />
        </svg></span> New Customer </h2>
    <form action="./invoice.php" onsubmit="check()" method="post">
      Name :<input type="text" name="name" required><br>
      Contact :<input type="number" name="phone" required><br>
      Email :<input type="text" name="email" required><br>
      Address :<input type="text" name="addr" required><br>
      <input type="submit" value="Submit" name="customer">
    </form>
  </div>
</div>


      <table class="table-bordered">
        <tr>
          <th style="width: 13%;"> Brand</th>
          <th style="width: 13%;"> Model</th>
          <th style="width: 20%;"> Price</th>
          <th style="width: 30%;"> Quantity taken</th>
          <th style="width: 15%;"> Total price</th>
        </tr>
      <tbody>
          <?php
            $Query = "SELECT * FROM `sale` where `status`!='sale'";
            $result = mysqli_query($conn, $Query);
            $num = mysqli_num_rows($result);   //to fetch the number of rows in table
            // mysqli_fetch_assoc($res) this function returns the next row in table 
            $var=0;
            if($num>0){
            while($row = mysqli_fetch_assoc($result)){ ?>
            <?php
                $bm = "SELECT * FROM `item` where `P_id`='$row[p_id]'";
                $bmr = mysqli_query($conn, $bm);
                $bmrow=mysqli_fetch_assoc($bmr);
            ?>
              <tr>
                <td> <?php echo $bmrow['Brand'];  ?> </td>
                <td> <?php echo $bmrow['Model'];  ?> </td>
                <td> <?php echo $bmrow['Price'];  ?> </td>
                <td> <?php echo $row['quantity'];  ?> </td>
                <td> <?php echo $x=$row['amount'];  ?></td>
                <?php $var=$var+$x; ?>
              </tr>  
            <?php } 
          }?>
          <tr>
            <td colspan=4 style="padding: 2px;">Total amount</td>
            <td border="1" cellpadding="4"><?php echo $var; ?></td>
          </tr>
      </tbody>
      </table>

      <div style="position:absolute;top:560px;left:590px;font:Times New Roman;">
      <?php
      if($var>=15000 && $discount!=0){
        if($discount==1){
          echo "<h3>The customer is availed a 5% discount!</h3>";
          $var=$var-(0.05*($var));
      }
        else if($discount==2){
          echo "<h3>The customer is availed a 10% discount!</h3>";
          $var=$var-0.1*($var);
      }
        else if($discount==3){
          echo "<h3>The customer is availed a 15% discount!</h3>";
          $var=$var-0.15*($var);
      }
        echo "<h5 align=center class='verified card'>Total amount after Discount: <em>Rs. ".$var."/-</em></h5>";
      }?>
      </div>

      <?php 
      if($flag){?>
      <div>
      <form action="invoice.php" method="post" style="position:absolute; top: 500px;right:500px;">
        Enter customer number:<select name="number"><option disabled selected value> -- select an option -- </option>
            <?php 
                $q="SELECT * from `customer`";
                $r=mysqli_query($conn, $q);
                while($row=mysqli_fetch_assoc($r)){?>
                <option value="<?php echo $row['contact'];?>"><?php echo $row['contact'];?></option>
            <?php }
            ?>
            </select>
        <input type="submit" value="Submit" name="cstmrveri">
        <?php }?>
        <?php
            $Query = "SELECT * FROM `sale` where `status`!='sale'";
            $result = mysqli_query($conn, $Query);
            $num = mysqli_num_rows($result);   //to fetch the number of rows in table
            // mysqli_fetch_assoc($res) this function returns the next row in table 
            $var=0;
            if($num>0){
            while($row = mysqli_fetch_assoc($result)){
              $q="UPDATE `sale` set `c_id`='$cid' where `sale_no`='$row[sale_no]'";
              $r=mysqli_query($conn, $q);
                ?>
            <?php } 
          }?>
          </form>
        </div>

    <?php 
    if($flag){?>
    <div id="Box">
    <div class="New-Box">
      <h2 style="text-align:center;"><span onclick="displayNone()"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" style="cursor:pointer;" />
          </svg></span> New Customer </h2>
      <form action="./Customer-page.php" onsubmit="check()" method="post">
        Name :<input type="text" name="name" required><br>
        Contact :<input type="number" name="phone" required><br>
        Email :<input type="text" name="email" required><br>
        Address :<input type="text" name="addr" required><br>
        <input type="submit" value="Submit">
      </form>
    </div>
  </div>
  <?php }?>

  <?php 
    if(!($flag)){?>
    <div class="verified card" style="position:absolute; top: 480px;right:600px; background-color:green; color:white">
    <div class="card-body" style="font-family:Sans-serif;font-size: 20px;"><i class="fa fa-check" style="font-size:30px;color:white"></i>&nbsp&nbsp&nbspCustomer Verified!</div>
    </div>
    <?php }?>


  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#" style="font-size: 5.5vh; font-family: cursive;">InvenTrack</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="Search" id="navbarCollapse">
          <form class="d-flex">
            <input class="Search-Bar" type="text" placeholder="Search" aria-label="Search">
            <input class="btn btn-outline-success" id="Button" type="submit" value="Search" style="background-color: rgb(7, 52, 186);
              color: white; margin: 4px;">Search</input>
          </form>

          <a href="./logout.php">Logout <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
              <path d="M7.5 1v7h1V1h-1z" />
              <path d="M3 8.812a4.999 4.999 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812z" />
            </svg></a>
        </div>
    </div>
  </nav>

  <main>
  <div>
    <ul>
        <li><a href="./Home-page.php">Home</a></li>
        <li><a href="./Customer-page.php">Customers</a></li>
        <li><a href="./Supplier-Page.php">Suppliers</a></li>
        <li><a href="../Project/Items.php">Items</a></li>
        <li><a href="../Project/Report.html">Sales</a></li>
    </ul>
  </div>

  <div class="New-Customer" onclick="displayBlock()" id="cv">
      <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 1 16 16">
          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
          <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
        </svg> New Customer</span>
    </div>
    

  <div class="New-Item">  
    <?php echo "<a href='GI.php?cid=$cid&dc=$discount'><span>Generate Invoice</span></a>";?>
    </div>
    </main>
</body>

</html>