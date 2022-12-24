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
  <link rel="stylesheet" href="./CSS/Items-Styling.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Items</title>
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
    function displayBlock() {
      document.getElementById('Box').style.display = "block";
    }

    function displayNone() {
      document.getElementById('Box').style.display = "none";
    }

    function check() {
      confirm('Submitting the Form, This will create a record')
    }
    function checkdel() {
      return confirm('Delete this record?');
    }
  
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
    function displaycart(){
      document.getElementById('Cart').style.display = "block";
    }
    function checksale(){
      return confirm('You won\'t be able to make changes once you go to the sales page. Confirm go to sales page?')
    }
  </script>

</head>

<body>
<?php

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include './Partials/_dbconnect.php';
    $type = $_POST["Type"];
    $brand = $_POST["Brand"];
    $model = $_POST["Model"];
    $description = $_POST["Description"];
    $cost = $_POST["Cost"];
    $price = $_POST["Price"];
    $quantity = $_POST["Quantity"];
    $d=date("Y/m/d");
    $tcost=$_POST["tcost"];
    $sno=$_POST["sno"];
    $f="SELECT * from `supplier` where `contact`='$sno'";
    $fr=mysqli_query($conn,$f);
    $findsupp=mysqli_fetch_assoc($fr);
    $sid=intval($findsupp["s_id"]);
    $pid=substr($type, 0, 1).substr($brand,0,2).substr($model, 0);
    $qry="SELECT * from `item` where `P_id`='$pid'";
    $res=mysqli_query($conn,$qry);
    $p=mysqli_fetch_assoc($res);
    if(mysqli_num_rows($res)==0){
      $sql = "INSERT INTO `item` (`P_id`, `Type`, `Brand`, `Model`, `Description`, `Cost`, `Price`, `Quantity`) VALUES ( '$pid', '$type', '$brand', '$model', '$description', '$cost', '$price', '$quantity')";
      $res = mysqli_query($conn, $sql);
    }
    else if($price!=$p["Price"]){

    }
    else{
      $qry="UPDATE  `item` set `quantity`=`quantity`+$quantity where `P_id`='$pid'";
      $result=mysqli_query($conn, $qry);
    }
    
    $sql ="INSERT INTO `purchase` (`date`, `quantity`, `transport_cost`, `s_id`, `p_id`) VALUES ('$d', '$quantity', '$tcost', '$sid', '$pid')";
    $res=mysqli_query($conn, $sql);
  }

  ?>
  
    <?php
    include './Partials/_dbconnect.php';
    $pid=$_GET['pid'];
    $qry="DELETE from `item` where `P_id`='$pid'";
    $result=mysqli_query($conn, $qry);
    if($result)
        echo"Deleted successfully";
    else
        echo"whoopsy";
    ?>

    <?php
    if(isset($_GET['choice'])){
      include './Partials/_dbconnect.php';
      if($_SERVER["REQUEST_METHOD"] == "GET"){
        if($_GET['choice'] == 'dec'){
          $q = "SELECT * from item order by price desc";
          $result = mysqli_query($conn, $q);
        }
        elseif($_GET['choice'] == 'inc'){
          $q = "SELECT * from item order by price asc";
          $result = mysqli_query($conn, $q);
        } 
      } 
    }
   ?>

   <?php
   if(isset($_GET['BRAND'])){
    include './Partials/_dbconnect.php';
      $b =  $_GET['BRAND'];
      $q = "SELECT * from item where Brand like '%$b%'";
      $result = mysqli_query($conn, $q);
    } 

   ?>
        
  <div id="Box">
    <div class="New-Box">
      <h2 style="text-align:center;"><span onclick="displayNone()"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" style="cursor:pointer;" />
          </svg></span> New Item </h2>
      <form action="./Items.php" onsubmit="check()" method="post">
        Supplier No.:<select name="sno"><option disabled selected value> -- select an option -- </option>
            <?php 
                $q="SELECT * from `supplier`";
                $r=mysqli_query($conn, $q);
                while($row=mysqli_fetch_assoc($r)){?>
                <option value="<?php echo $row['contact'];?>"><?php echo $row['contact'];?></option>
            <?php }
            ?>
        <br><br>Type :<input type="text" name="Type" required><br>
        Brand :<input type="text" name="Brand" required><br>
        Model :<input type="text" name="Model" required><br>
        Description :<input type="text" name="Description" required><br>
        Cost :<input type="number" name="Cost" required><br>
        Transport Cost :<input type="text" name="tcost" required><br>
        Price :<input type="number" name="Price" required><br>
        Quantity :<input type="number" name="Quantity" required><br>
        <input type="submit" value="Submit">
      </form>
    </div>
  </div>

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
        <li><a href="./Items.php">Items</a></li>
        <li><a href="./sales.php">Sales</a></li>
    </ul>
  </div>

  <div id="sort" >
    <form method="get" action="./Items.php">
       Sort by:<select name="choice"><option disabled selected value> -- select an option -- </option>
            <option value="inc">Increasing</option>
            <option value="dec">Decreasing</option>
            </select>
            <input type="submit" value="SUBMIT" style=" background-color: rgb(7, 52, 186); color:white;">
    </form>
  </div>

  <div id="Brand-Box" >
    <form method="get" action="./Items.php">
      Search for a  Brand:<input type="text" name="BRAND" >
            <input type="submit" value="SUBMIT" style=" background-color: rgb(7, 52, 186); color:white;">
    </form>
  </div>

  <div style="overflow-x:auto;">
      <table>
        <tr>
          <th style="width: 11%;"> Product no.</th>
          <th style="width: 11%;"> Type</th>
          <th style="width: 13%;"> Brand</th>
          <th style="width: 20%;"> Model</th>
          <th style="width: 30%;"> Price</th>
          <th style="width: 30%;"> Add to Sale</th>
          <th style="width: 15%;"> Quantity in stock</th>
          <th></th>
        </tr>
      <tbody>
          <?php
            include './Partials/_dbconnect.php';
            if(!isset($_GET['choice']) and !isset($_GET['BRAND'])){
            $Query = "SELECT * FROM `item`";
            $result = mysqli_query($conn, $Query); }

            $num = mysqli_num_rows($result);   //to fetch the number of rows in table

            // mysqli_fetch_assoc($res) this function returns the next row in table 
            if($num>0){
            while($row = mysqli_fetch_assoc($result)){ ?>
              <tr>
                <td> <?php echo $row['P_id'];  ?> </td>
                <td> <?php echo $row['Type'];  ?> </td>
                <td> <?php echo $row['Brand'];  ?> </td>
                <td> <?php echo $row['Model'];  ?> </td>
                <td> <?php echo $row['Price'];  ?> </td>
                <td> <?php echo "<a href='removefromcart.php?pid=$row[P_id]'><button style='font-size:24px background-color: transparent;' class='fa'>&#xf068</button></a> &nbsp&nbsp";?>
                    <?php
                        $q="SELECT * from `sale`";
                        $r=mysqli_query($conn, $q);
                        if(mysqli_num_rows($r)==0){
                          echo "0";
                        }
                        else{
                          $q="SELECT * from `sale` where `p_id`='$row[P_id]' and `status`='cart'";
                          $r=mysqli_query($conn,$q);
                          if(mysqli_num_rows($r)==0){
                            echo "0";
                          }
                          else{
                            $q=mysqli_fetch_assoc($r);
                            echo $q["quantity"];
                          }
                        }
                    ?>
                  <?php  echo "<a href='addtocart.php?pid=$row[P_id]'><button style='font-size:24px background-color: transparent;' class='fa'>&#xf067;</button></a>"; ?>
                <td> <?php echo $row['Quantity'];  ?> </td>
                <td><?php echo "<a href='Items.php?pid=$row[P_id]' onclick='return checkdel()'><button class=\"del\">Delete</button></a>" ?></td>
              </tr>  
            <?php } 
          }?>
           
      </tbody>
      </table>
    </div>


    <div class="gotocart">
    <a href="invoice.php"><span>
      <?php
        $q="SELECT * from `sale` where `status`='cart'";
        $r=mysqli_query($conn,$q);
        echo mysqli_num_rows($r);
      ?>
      <i style='font-size:24px background-color: transparent;' class='fa'>&#xf07a;</i><br>Proceed to sale</span></a>
    </div>


    <div class="New-Item" onclick="displayBlock()">
      <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 1 16 16">
          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
          <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
        </svg> New Item</span>
    </div>
        </main>

    <footer>
      <p style="padding-bottom: 90%;"></p>
        </footer>
</body>

</html>