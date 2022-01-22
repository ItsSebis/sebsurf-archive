<?php
  include_once "header.php";
  if (empty($_SESSION["username"])) {
    header("location: ./");
    exit();
  }
 ?>
  <script type="text/javascript">
    document.getElementById("profile").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
  </script>
    <div class="main">
    <h1>Deine Events</h1><br>
    <form action="includes/downloaddata.inc.php" target="_blank" method="post">
      <button type="submit" name="submit">Download</button>
      <?php teamsListMember($con); ?>
    </form>
    <form action="profile.php" method="post">
      <button type="submit" name="submit">Filter</button>
      <?php teamsListMember($con); ?>
    </form>
    <?php
      if (isset($_POST["team"]) && $_POST["team"] != "null") {
        $teamName = teamData($con, $_POST["team"])["name"];
        echo("<p>Filtered by team: '".$teamName."'</p>");
    }
    ?>
    <table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
      <thead>
        <tr>
          <th style="padding-left: 10px; padding-right: 10px;">ID</th>
          <th style="padding-left: 10px; padding-right: 10px;">Name</th>
          <th style="padding-left: 10px; padding-right: 10px;">Team</th>
          <th style="padding-left: 10px; padding-right: 10px;">Dauer</th>
          <th style="padding-left: 10px; padding-right: 10px;">Datum</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $team = "null";
        if (!empty($_POST["team"])) {
          $team = $_POST["team"];
        }
        datas($con, $_SESSION["username"], $team);
      ?>
      <tr>
        <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
        <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
        <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
        <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
        <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black; color: #262626;"><p style="visibility: hidden;">moin :)</p></td>
      </tr>
      <tr>
          <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
          <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
          <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black; font-weight: bold;">Gesamt: </td>
          <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black; font-weight: bold;"><?php
          $team = "null";
          if (!empty($_POST["team"])) {
            $team = $_POST["team"];
          } 
          echo(getAllLessonsCount($con, $_SESSION["username"], $team)." Schulstunden"); ?></td>
          <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
      </tr>
      </tbody>
    </table>

    </div>
    <div class="main">
      <form action="includes/datamanager.inc.php" method="post">
        <input type="number" name="id" placeholder="ID..."><br>
        <input type="text" name="name" placeholder="Name..."><br>
        <?php teamsListMember($con); ?>
        <input type="number" name="lessons" placeholder="Schulstunden..."><br>
        <input type="datetime-local" name="date" placeholder="Datum..." style="width: 250px;"><br>
        <button type="submit" name="add">Hinzufügen</button><br><br>
        <button type="submit" name="edit">Bearbeiten</button><br><br>
        <button type="submit" name="del">Löschen</button>
      </form>
      <?php
            if (isset($_GET["error"])) {
              if ($_GET["error"] == "dataadded") {
                echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Added data!</p>";
                echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Event '".$_GET["name"]."' was added to the database!!</p>";
              } elseif ($_GET["error"] == "error") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
              } elseif ($_GET["error"] == "emptyf") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Please fill in every field!";
              } elseif ($_GET["error"] == "dataedited") {
                echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Edited data!</p>";
                echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Event '".$_GET["name"]."' was edited in the database!</p>";
              } elseif ($_GET["error"] == "eerror") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
              } elseif ($_GET["error"] == "eemptyf") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Important field was empty!";
              } elseif ($_GET["error"] == "deldata") {
                echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Deleted data!</p>";
                echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Event '".$_GET["name"]."' was deleted from the database!</p>";
              } elseif ($_GET["error"] == "invalid") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>The name may not contain <br>'<' and '>'!</p>";
              }
            }
      ?>
    </div>
    <div class="main">
      <h2>Notes:</h2><br>
      <form action="includes/usermanager.inc.php" method="post">
        <textarea style="background: none" name="note" cols="100" rows="30"><?php echo(userData($con, $_SESSION["username"])["note"]); ?></textarea><br><br>
        <button type="submit" name="savenote">Sichern</button>
      </form>
      <?php
            if (isset($_GET["error"])) {
              if ($_GET["error"] == "notesaved") {
                echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Saved Changes!</p>";
              } elseif ($_GET["error"] == "inboundoutofcharacter") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Note cannot be longer than 2000 character!</p>";
              }
            }
      ?>
    </div>
  </body>
</html>
