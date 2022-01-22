<?php
  include_once 'header.php';
  if (!isset($_SESSION["username"])) {
    header("location: log-in.php");
    exit();
  }
 ?>
  <?php
  if (isset($_GET["error"])) {
    if ($_GET["error"] == "0") {
      echo "<p style='color: lime; border: solid green; max-width: 500px; text-align: center; margin: 10px auto; border-radius: 7px;'>Du hast dich erfolgreich eingelogt als '".$_SESSION['username']."'!</p>";
    }
    elseif ($_GET["error"] == "1") {
      echo "<p style='color: red; border: solid red; max-width: 450px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal error: Something went wrong!";
    }
    elseif ($_GET["error"] == "noperm") {
      echo "<p style='color: red; border: solid red; max-width: 300px; text-align: center; margin: 10px auto; border-radius: 7px;'>Dir ist es nicht erlaubt diese Seite zu betreten!!";
    }
  }
   ?>
   <script type="text/javascript">
     document.getElementById("home").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
   </script>
   <div class="main" style="max-width: 200px; width: auto;">
     <p>Moin <?php echo($_SESSION["nick"]); ?>!</p>
   </div>
   

  <?php 
  if (currentNewsData($con) !== false) {?>
  <div class="main">
    <h2>Neues:</h2><br>
    <?php 
      $data = newsData($con, currentNewsData($con)["id"]);
      echo("
        <p style='color: grey;'>Veröffentlicht: ".$data['date']." (GMT)</p><br>
        <p style='color: lime; max-width: 700px; font-align: center; border: solid #202020; margin: 0 auto; border-radius: 15px; padding: 10px; width: fit-content; background-color: #2f2f2f'>".$data['news']."</p><br>
        <p style='color: grey;'>von ".$data['publisher']." (".roleData($con, userData($con, $data['publisher'])['role'])['name'].")</p><br>
      ");
    ?>
  </div><?php }
  
  /* ## Leaderboard Users ##
  
   <div class="main">
   <h1>Leaderboard</h1>
    <table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
    <thead>
      <tr>
        <th style="padding-left: 10px; padding-right: 10px;">User</th>
        <th style="padding-left: 10px; padding-right: 10px;">Lessons</th>
      </tr>
    </thead>
    <tbody><br>
    <?php
      leaderboard($con);
    ?>
  </tbody>
  </table>
  </div>
  */
  
  if (getUserPower($con, $_SESSION["username"]) > 100) {
  ?>
  <div class="main">
    <h2>Updates:</h2>
    <?php versions($con); ?>
  </div>
  <?php
  }
  ?>
  </body>
</html>
