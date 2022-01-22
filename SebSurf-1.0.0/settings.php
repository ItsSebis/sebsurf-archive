<?php
  include_once "header.php";
  if (empty($_SESSION["username"])) {
    header("location: ./");
    exit();
  }
 ?>
 <script type="text/javascript">
   document.getElementById("settings").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
 </script>
 <div class="main" style="max-width: 300px;">
   <form action="includes/usermanager.inc.php" method="post">
     <input type="text" name="nick" placeholder="Nickname..."><br>
     <button type="submit" name="setnick" style="margin-bottom: 7px;">Set Nickname</button><br>
   </form>

   <?php 
         if (isset($_GET["error"])) {
           if ($_GET["error"] == "setnick") {
             echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Changed Nickname!</p>";
           } elseif ($_GET["error"] == "inboundoutofcharacter") {
             echo "<p style='color: red; border: solid red; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Nickname too long!</p>";
             echo "<p style='color: red; border: solid red; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Max. 22 characters!</p>";
           } elseif ($_GET["error"] == "invalid") {
            echo "<p style='color: red; border: solid red; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Nickname may not contain <br>'<' and '>'!</p>";
          }
         }
   ?>
 </div>
 <?php
 if (isset($_GET["error"])) {
  if ($_GET["error"] == "0") {
    echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Shut down licence!</p>";
    echo "<p class='hidden' style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["licence"]."</p>";
  } elseif ($_GET["error"] == "notyours") {
    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>This is not your licence!";
  } elseif ($_GET["error"] == "notused") {
    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>This licence is not used!";
  } elseif ($_GET["error"] == "verified") {
    echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Verified licence!</p>";
    echo "<p class='hidden' style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["licence"]."</p>";
  } elseif ($_GET["error"] == "licencegiven") {
    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>This licence is given!";
  } elseif ($_GET["error"] == "nolicence") {
    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>This licence does not exist!";
  }
 }
  ?>
    <div class="main">
      <form action="includes/usermanager.inc.php" method="post">
        <input type="password" name="pw" placeholder="Password..."><br>
        <input type="password" name="rpw" placeholder="Repeat Password..."><br>
        <button type="submit" name="setpw" style="margin-bottom: 7px;">Set Password</button><br>
      </form>

      <?php 
            if (isset($_GET["error"])) {
              if ($_GET["error"] == "pwset") {
                echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Changed Password!</p>";
              } elseif ($_GET["error"] == "repeatpw") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Please put your new password in both fields!";
              } elseif ($_GET["error"] == "invalidrpw") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Theese Passwords do not match!";
              }
            }
            ?>
    </div>
  </body>
</html>
