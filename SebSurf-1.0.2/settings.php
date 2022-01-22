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
             echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Spitzname geändert!</p>";
           } elseif ($_GET["error"] == "inboundoutofcharacter") {
             echo "<p style='color: red; border: solid red; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Spitzname zu lang!</p>";
             echo "<p style='color: red; border: solid red; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Max. 22 Zeichen!</p>";
           } elseif ($_GET["error"] == "invalid") {
            echo "<p style='color: red; border: solid red; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Spitznamen dürfen 
            folgende Zeichen nicht enthalten <br>'<' and '>'!</p>";
          }
         }
   ?>
 </div>
    <div class="main">
      <form action="includes/usermanager.inc.php" method="post">
        <input type="password" name="oldpw" placeholder="Altes Passwort..."><br>
        <input type="password" name="pw" placeholder="Neues Passwort..."><br>
        <input type="password" name="rpw" placeholder="Passwort wiederholen..."><br>
        <button type="submit" name="setpw" style="margin-bottom: 7px;">Passwort setzen</button><br>
      </form>

      <?php
        if (isset($_GET["error"])) {
          if ($_GET["error"] == "pwset") {
            echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Passwort geändert!</p>";
          } elseif ($_GET["error"] == "repeatpw") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Du musst dein neues Passwort bestätigen!";
          } elseif ($_GET["error"] == "invalidrpw") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Passwörter stimmen nicht überein!";
          } elseif ($_GET["error"] == "wrongpw") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Dieses alte Passwort ist inkorrekt!";
          }
        }
      ?>
    </div>
  </body>
</html>
