<?php
  include_once 'header.php';
  if (!isAdmin($con, $_SESSION["username"])) {
    header("location: index.php?error=noperm");
    exit();
  }
   else {
    $error = "";
    if(isset($_POST['submit_pass']) && $_POST['pass']) {
      $pass = $_POST["pass"];
      if (adminPwMatch($con, $_SESSION["username"], $pass)) {
        $_SESSION["adminentry"] = true;
      } else {
        $error = "Wrong Password!";
      }
    }
  }
 ?>
 <script type="text/javascript">
   document.getElementById("admin").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
 </script>
<?php
if ($_SESSION["adminentry"] == true) {
  #####################################################################################################
  ?>
    <div class="main">
      <form action="includes/admin.inc.php" method='post'>
        <button type='submit' name='users'>Benutzer</button>
        <button type='submit' name='roles'>Rollen</button>
        <button type='submit' name='teams'>Teams<?php if (getAllRequestsCount($con) != 0) {echo(" <span style='color: black; border: solid red; border-radius: 15px; 
          background-color: red'>".getAllRequestsCount($con)."</span>");} ?></button>
        <button type='submit' name='news'>Neuigkeiten</button>
        <?php /*
        <?php
          if (getUserPower($con, $_SESSION["username"]) >= 115) {
        ?>
        <button type='submit' name='updates'>Server Updates</button><?php }?>*/?>
        <br><br>
        <button type='submit' name='adminlogout' style='border-color: red;'>Logout Admin</button>
      </form>
    </div>
    <?php 
      if (!isset($_GET["page"]) || $_GET["page"] == "users") {
        if ((!isset($_GET["usr"]) || userData($con, $_GET["usr"]) === false) && !isset($_GET["create"])) {
    ?>

    <div class='main'>

    <h1>Benutzer</h1>
    <form action="admin.php">
      <button type='submit' name='create'>Hinzufügen</button>
    </form>
    <?php
      users($con);
    ?>
    
    <?php 
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "delusr") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Benutzer gelöscht!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>".$_GET["acc"]."</p>";
      } elseif ($_GET["error"] == "systemroot") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Du kannst doch nicht root bearbeiten???</p>!";
      } elseif ($_GET["error"] == "usercreated") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Benutzer erstellt!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>".$_GET["name"]."</p>";
      }
    }?>
    </div>

  </div>

  <?php
      } elseif (isset($_GET["usr"])) {?>

    <div class="main">
      <a href="admin.php" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
      <h1 style="margin-top: 20px; font-size: 3rem"><?php echo($_GET["usr"]); ?></h1>
      <form action="includes/usermanager.inc.php" method="post">
        <input type="text" name="user" placeholder="Account..." hidden="1" style="width: 500px;" value="<?php echo($_GET["usr"]); ?>">
        <?php /*<input type="text" name="newacc" placeholder="Account..." style="width: 500px;" value="<?php echo($_GET["usr"]); ?>"><br> */?>
        <input type="text" name="fullname" placeholder="Voller Name..." value="<?php echo(userData($con, $_GET["usr"])["fullname"]); ?>"><br>
        <input type="text" name="nick" placeholder="Nickname..." value="<?php echo(userData($con, $_GET["usr"])["nick"]); ?>"><br>
        <input type="text" name="pw" placeholder="Passwort..."><br>
        <?php
          userActiveList($con, $_GET["usr"]);
          if (getUserPower($con, $_SESSION["username"]) >= 110) {
            rolesListUser($con, $_GET["usr"]);
          }
        ?>
        <input type="checkbox" name="admin" checked="1" hidden="1">
        <button type="submit" name="edit" style="margin-bottom: 7px;">Bearbeiten</button><br>
        <button type="submit" name="del">Löschen</button><br><br>
      </form>
    <?php 
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "nopassword") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>No Password given!";
      } elseif ($_GET["error"] == "delself") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Lösch dich... nicht!</p>";
      } elseif ($_GET["error"] == "lesspower") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Dafür hast du nicht genug Power!</p>!";
      } elseif ($_GET["error"] == "delusr") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Benutzer gelöscht!</p>";
      } elseif ($_GET["error"] == "nouser") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Dieser Benutzer existiert nicht!</p>";
      } elseif ($_GET["error"] == "userexists") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Es existiert bereits ein Benutzer mit diesem Namen!</p>";
      } elseif ($_GET["error"] == "usercreated") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Benutzer erstellt!</p>";
      } elseif ($_GET["error"] == "usredited") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Benutzer bearbeitet!</p>";
      } elseif ($_GET["error"] == "charlimitreached") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Benutzernamen dürfen nicht länger als 64 Zeichen sein!</p>";
      }
    }?>
    </div>
       
       <?php
      } else {?>
        <div class="main">
          <a href="admin.php" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
          <h1 style="margin-top: 20px; font-size: 3rem">Create User</h1>
          <form action="includes/usermanager.inc.php" method="post">
            <input type="text" name="user" placeholder="Account..." style="width: 500px;"><br>
            <input type="text" name="fullname" placeholder="Voller Name..."><br>
            <input type="number" name="disabled" value="0" hidden="1">
            <?php
              if (getUserPower($con, $_SESSION["username"]) >= 110) {
                rolesList($con);
              }
            ?>
            <input type="checkbox" name="admin" checked="1" hidden="1">
            <button type="submit" name="create">Hinzufügen</button><br><br>
        <?php 
        if (isset($_GET["error"])) {
          if ($_GET["error"] == "emtpyf") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Fülle bitte alle Felder!";
          } elseif ($_GET["error"] == "userexists") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Es gibt bereits einen User mit diesem Namen!</p>";
          }
        }?>
        </div>
        <?php
      }
    } elseif ($_GET["page"] == "roles") {
  ?>

<div class='main'>
  <h1>Rolles</h1>
  <table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
  <thead>
    <tr>
      <th style="padding-left: 10px; padding-right: 10px;">ID</th>
      <th style="padding-left: 10px; padding-right: 10px;">Name</th>
      <th style="padding-left: 10px; padding-right: 10px;">Erstellt von</th>
      <th style="padding-left: 10px; padding-right: 10px;">Power</th>
    </tr>
  </thead>
  <tbody><br>
  <?php
    roles($con);
  ?>
  </tbody>
  </table>
  </div>
  <div class="main">
      <form action="includes/rolemanager.inc.php" method="post">
        <input type="number" name="role" placeholder="Id..." style="width: 500px;"><br>
        <?php
        #rolesList($con);
        ?>
        <input type="text" name="name" placeholder="Name..."><br>
        <input type="number" name="power" placeholder="Power..."><br>
        <button type="submit" name="edit" style="margin-bottom: 7px;">Bearbeite Rolle</button><br>
        <button type="submit" name="create" style="margin-bottom: 7px;">Erstelle Rolle</button><br>
        <button type="submit" name="del">Lösche Rolle</button><br><br>
      </form>
    <?php 
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "editedrole") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Rolle bearbeitet!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["name"]."</p>";
      } elseif ($_GET["error"] == "del") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Rolle gelöscht!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["name"]."</p>";
      } elseif ($_GET["error"] == "norole") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle gibt es nicht!</p>";
      } elseif ($_GET["error"] == "roleexists") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle gibt es bereits!</p>";
      } elseif ($_GET["error"] == "rolecreated") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Rolle Erstellt!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["name"]."</p>";
      } elseif ($_GET["error"] == "emptyf") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Wichtige Felder waren leer!</p>";
      } elseif ($_GET["error"] == "lesspower") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Dafür hast du nicht genug Power!</p>!";
      } elseif ($_GET["error"] == "protrole") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle ist vom System geschützt!</p>";
      } elseif ($_GET["error"] == "toohighpower") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Die maximale Power ist 127!</p>";
      }
    }?>
    </div>
  </div>
</div>

    <?php
    } elseif ($_GET["page"] == "updates") {
      if (getUserPower($con, $_SESSION["username"]) < 128) {
        header("location: admin.php");
        exit();
      }
      ?>

    <div class="main">
    <h1>Update</h1>
    <form action="includes/versionmanager.inc.php" method="post">
        <label for="1">Version:</label>
        <input type="number" name="1" placeholder="1" style="width: 50px;" value=<?php echo(explode(".", currentVersionData($con)["version"])[0]); ?>> .
        <input type="number" name="2" placeholder="2" style="width: 50px;" value=<?php echo(explode(".", currentVersionData($con)["version"])[1]); ?>> .
        <input type="number" name="3" placeholder="3" style="width: 50px;" value=<?php echo(explode(".", currentVersionData($con)["version"])[2] + 1); ?>><br>
        <input type="text" name="des" placeholder="Description..."><br>
        <button type="submit" name="add" style="margin-bottom: 7px;">Veröffentlichen!</button><br>
      </form>
    <?php 
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "addedupdate") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Published an update!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["v"]."</p>";
      } elseif ($_GET["error"] == "toolong") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>The maximum amount of characters is 64!";
      } elseif ($_GET["error"] == "emptyf") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Please fill in all fields!";
      }
    }?>
    </div>

<?php
    } elseif ($_GET["page"] == "news") {?>

      <div class="main">
      <h1>Edit News</h1>
      <form action="includes/newsmanager.inc.php" method="post">
        <br><textarea name="news" id="news" placeholder="Send news to the users!" cols="50" rows="20" style="color: #FFF; background: none;"></textarea><br><br>
        <button type="submit" name="add" style="margin-bottom: 7px;">Veröffentlichen!</button><br>
        <button type="submit" name="del" style="margin-bottom: 7px;">Neuigkeiten Löschen</button><br>
      </form>
      <?php 
      if (isset($_GET["error"])) {
        if ($_GET["error"] == "edited") {
          echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Veröffentlicht!</p>";
        } elseif ($_GET["error"] == "toolong") {
          echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Neuigkeiten dürfen nicht mehr als 2000 
          Zeichen haben!";
        } elseif ($_GET["error"] == "emptyf") {
          echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Please fill in all fields!";
        } elseif ($_GET["error"] == "cleaned") {
          echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Cleaned News!</p>";
        }
      }?>
      </div>

    <?php
    } elseif ($_GET["page"] == "teams") {?>
      <div class="main">
        <h1>Team-Verwaltung</h1>
        <?php
          teams($con);
        ?>
      </div>

      <div class="main">
        <h1>Team Anfragen</h1>
        <?php
          allTeamRequests($con);
        ?>
        <?php 
        if (isset($_GET["error"])) {
          if ($_GET["error"] == "accepted") {
            echo "<br><p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 
            7px; margin-bottom: 10px;'>Team Erstelung genemigt! Name: '".$_GET['team']."'!</p>";
          } elseif ($_GET["error"] == "denied") {
            echo "<br><p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 
            7px; margin-bottom: 10px;'>Team Erstelung abgelehnt! Name: '".$_GET['team']."'!</p>";
          }
        }?>
      </div>

      <div class="main">
        <form action="includes/teammanager.inc.php" method="post">
          <input type="text" name="name" id="team" placeholder="Team Name..." style="width: 500px;"><br>
          <?php
            teamsList($con);
          ?>
          <button type="submit" name="create">Etstellen</button><br><br>
          <button type="submit" name="delete">Löschen</button>
        </form>
        <?php 
        if (isset($_GET["error"])) {
          if ($_GET["error"] == "c") {
            echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Team Erstellt '".$_GET['team']."'!</p>";
          } elseif ($_GET["error"] == "toolong") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Teamnamen können nicht mehr als 64 Zeichen haben!";
          } elseif ($_GET["error"] == "emptyf") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Fülle bitte alle Felder!";
          } elseif ($_GET["error"] == "del") {
            echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Team gelöscht '".$_GET['team']."'!</p>";
          }
        }?>
      </div>

<?php
    }
} else {
  #####################################################################################################
      ?>
        <form class="log-in" method="post" action="" id="login_form">
            <h1>Weise dich bitte erneut aus!</h1>
            <p style="color: red; font-weight: 900;">Du betrittst Administrative Seiten!</p>
            <input type="password" name="pass" placeholder="Enter Password!">
            <button type="submit" name="submit_pass">LOG IN</button>
            <p>
                <?php echo "<br><p style='color: red;'>".$error."</p>"; ?>
            </p>
        </form>
        <?php
}
?>

  </body>
</html>
