<?php
  session_start();
  if(!isset($_SESSION['loggedin'])){
    header("location: ./login_page.php");
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./CSS/Customer-Styling.css" />
  <title>Customer Details</title>
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />

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
  </script>

</head>

<body>
  <?php

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include './Partials/_dbconnect.php';

    $name = $_POST["name"];
    $contact = $_POST["phone"];
    $email = $_POST["email"];
    $address = $_POST["addr"];

      $sql = "INSERT INTO `customer` ( `name`, `contact`, `email`, `address`, `discoins`) VALUES ( '$name', '$contact', '$email', '$address', '0')";
      $res = mysqli_query($conn, $sql);
      
  }

  ?>

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

  <?php
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
          include './Partials/_dbconnect.php';
          $name=$_GET['customer'];
          $q = "SELECT * from customer where name like '%$name%'";
          $result = mysqli_query($conn, $q);

        }
      ?>

  <header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#" style="font-size: 5.5vh; font-family: cursive;">InvenTrack</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="Search" id="navbarCollapse">
          <form class="d-flex" method="get" acion="./Customer-page.php">
            <input class="Search-Bar" type="text" name="customer" placeholder="customer-Search" aria-label="Search">
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
  </header>

  <main>
  
    <div style="overflow-x:auto;">
      <table>
        <tr>
          <th style="width: 11%;"> Customer-ID</th>
          <th style="width: 17%;"> Name</th>
          <th style="width: 13%;"> Contact Number</th>
          <th style="width: 20%;"> Email</th>
          <th style="width: 30%;"> Address</th>
          <th style="width: 15%;"> Discoins</th>
        </tr>
      <tbody>
          <?php
            include './Partials/_dbconnect.php';
            if(!isset($_GET['customer'])){
            $Query = "SELECT * FROM `customer`";
            $result = mysqli_query($conn, $Query);
            }

            $num = mysqli_num_rows($result);   //to fetch the number of rows in table

            // mysqli_fetch_assoc($res) this function returns the next row in table 
            if($num>0){
            while($row = mysqli_fetch_assoc($result)){ ?>
              <tr>
                <td> <?php echo $row['c_id'];  ?> </td>
                <td> <?php echo $row['name'];  ?> </td>
                <td> <?php echo $row['contact'];  ?> </td>
                <td> <?php echo $row['email'];  ?> </td>
                <td> <?php echo $row['address'];  ?> </td>
                <td> <?php echo $row['discoins'];  ?> </td>
              </tr>  
            <?php } 
          }?>
           
      </tbody>
      </table>
    </div>

    <div>
      <ul>
        <li><a href="./Home-page.php">Home</a></li>
        <li><a href="./Customer-page.php">Customers</a></li>
        <li><a href="./Supplier-Page.php">Suppliers</a></li>
        <li><a href="./Items.php">Items</a></li>
        <li><a href="./Sales.php">Sales</a></li>
      </ul>
    </div>

    <div class="New-Customer" onclick="displayBlock()">
      <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 1 16 16">
          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
          <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
        </svg> New Customer</span>
    </div>
  </main>
</body>
</html>