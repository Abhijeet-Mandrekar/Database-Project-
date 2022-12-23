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
    <link rel="stylesheet" href="./CSS/home-page-styling.css" />
    
    <!-- CSS only -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi"
      crossorigin="anonymous" />

      <script type="text/javascript">
        function validateForm(thisform)
        {
            var s=thisform.Search.value;
            if(s="" )
            {
              alert("Please Enter an Item");
              thisform.Search.focus();
              return false;
            }
            
        }
      </script>

     

<title>Welcome </title>

  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <div class="container-fluid" >
        <a class="navbar-brand" href="#" style="font-size: 5.5vh;">InvenTrack</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" 
          aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="Search" id="navbarCollapse">
          <form class="d-flex">
            <input class="Search-Bar" type="text" placeholder="Search" aria-label="Search">
            <input class="btn btn-outline-success" id="Button" type="submit" value="Search" style="background-color: rgb(7, 52, 186);
              color: white; margin: 4px;"></input>
          </form>

          <a href="./logout.php">Logout  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" 
              fill="currentColor" class="bi bi-power"  viewBox="-1 0 16 16">
            <path d="M7.5 1v7h1V1h-1z"/>
            <path d="M3 8.812a4.999 4.999 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812z"/>
          </svg></a>  
        </div>
      </div>
    </nav>

    <div class="sales">
       <p> Welcome Back Administartor </p>
       <hr>
       <em> Total Sales Today </em>
        <hr>
        <?php
          include './Partials/_dbconnect.php';
          // query to add the sum of all the sales of today
          $q="SELECT sum(amount) as sales from sale where status='sale'";
          $res = mysqli_query($conn, $q);
          $row = mysqli_fetch_assoc($res);
          echo $row['sales'];
        ?>
    </div>

    <div>
      <ul> 
          <li><a href="./Home-page.php" >Home</a></li>
          <li><a href="./Customer-page.php">Customers</a></li>
          <li><a href="./Supplier-Page.php">Suppliers</a></li>
          <li><a href="./Items.php">Items</a></li>
          <li><a href="./sales.php">Sale</a></li>
          <li><a href="./Aboutus.html">About Us</a></li>
      </ul>
    </div>
  

  </body>
</html>
