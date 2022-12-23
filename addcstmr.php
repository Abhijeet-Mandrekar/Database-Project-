<!doctype html>
<html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include './Partials/_dbconnect.php';

  $name = $_POST["name"];
  $contact = $_POST["phone"];
  $email = $_POST["email"];
  $address = $_POST["addr"];

    $sql = "INSERT INTO `customer` ( `name`, `contact`, `email`, `address`, `discoins`) VALUES ( '$name', '$contact', '$email', '$address', '0')";
    $res = mysqli_query($conn, $sql);
    
}?>
<div id="Box">
  <div class="New-Box">
    <h2 style="text-align:center;"><span onclick="displayNone()"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" style="cursor:pointer;" />
        </svg></span> New Customer </h2>
    <form action="./addcstmr.php" onsubmit="check()" method="post">
      Name :<input type="text" name="name" required><br>
      Contact :<input type="number" name="phone" required><br>
      Email :<input type="text" name="email" required><br>
      Address :<input type="text" name="addr" required><br>
      <input type="submit" value="Submit">
    </form>
  </div>
</div>
</html>