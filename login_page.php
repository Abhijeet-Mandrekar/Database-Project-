<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/login-styling.css">
    
    <title> Welcome to InvenTrack </title>

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" 
      integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

<script>
function validateUrPw(thisform) 
{ 
	var userName= thisform.uname.value; 
	var pass1= thisform.pw1.value; 
	if(userName=="")
	{
		alert("Please enter the user name"); 
		thisform.uname.focus();
		return false;
	}
	if(pass=="") 
	{
		alert("Please enter the password"); 
		thisform.pw1.focus(); 
		return false;
	}
	return true;
}
</script>

    <?php
      if($_SERVER["REQUEST_METHOD"] == "POST"){
        include './Partials/_dbconnect.php';
        $username = $_POST["username"];
        $password = $_POST["password"];

        $sql = "Select * from admin where username ='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);

        if($num == 1){  
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username ?>

            <script>
               alert('Logged In');
            </script>
        <?php    
           header("location: ./Home-page.php");
        }
      }
    ?>

</head>

<body>
    
    <!-- FOR BOOTSTRAP -->
    <div id="carouselExampleSlidesOnly" class="image-box carousel-fade left-carousel" data-ride="carousel" data-interval="2000">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="image" src="./kisspng-inventory-management-software-warehouse-management-rack-vector-5b067d82259942.506115281527152002154.jpg" 
                alt="First slide">
          </div>
          <div class="carousel-item">
            <img class="image" src="./inventory-management-mistakes-6.jpg" alt="Second slide">
          </div>
        </div>
      </div>
    <div class="quote">
        <p style="font-size: 18px;"> " The More Inventory a Company has, the Less Likely They will have what they need " </p>
    </div>

    <div class="login-box">
     <p style="font-size: 8vh;"> InvenTrack</p>
     <form action="./login_page.php" method="post"  onsubmit="return validateUrPw(this)">
        <p style="font-size: 3vh; margin-top: 5px;">UserName</p>
        <input type="text" id="emailid" placeholder="Username" name="username" required>
        <p style="font-size: 3vh; margin-top: 5px;">Password</p>
        <input type="password" id="pass" placeholder="Password" name="password" required>
        <br><br>
        <input type="submit" id="submit" value="Login" >
    </form>
    </div>    

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"   crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"    crossorigin="anonymous"></script>

</body>

</html>