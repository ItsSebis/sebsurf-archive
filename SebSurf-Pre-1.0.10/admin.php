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
        <button type='submit' name='groups'>Gruppen</button>
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

    <h1>Benutzer</h1><br>
    <form action="admin.php">
      <button type='submit' name='create'>Hinzufügen</button>
    </form><br>
    <form action="admin.php">
      <?php
      if (isset($_GET["facc"])) {
        $facc = $_GET["facc"];
        $space = " ";
      } else {
        $facc = "";
      }
      echo '<input type="text" name="facc" placeholder="Accountname..." style="width: 320px; height: 38px;" value="'.$facc.'">';
      ?>
      <?php
        rolesList($con);
      ?>
      <button type='submit'>Filtern</button>
    </form>
    <?php
    if (isset($_GET["facc"]) && isset($_GET["role"]) && ($_GET["role"] != "null" || roleData($con, $_GET["role"]) !== false)) {
      $facc = $_GET["facc"];
      $role = $_GET["role"];
      usersFiltered($con, $facc, $role);
    } else {
      users($con);
    }
    ?>
    
    <?php 
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "delusr") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Benutzer gelöscht!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>".$_GET["acc"]."</p>";
      } elseif ($_GET["error"] == "systemroot") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Du kannst doch nicht root bearbeiten???</p>!";
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
        <input type="text" name="newacc" placeholder="Account..." style="width: 500px;" value="<?php echo($_GET["usr"]); ?>"><br>
        <input type="text" name="fullname" placeholder="Voller Name..." value="<?php echo(userData($con, $_GET["usr"])["fullname"]); ?>"><br>
        <?php /*<input type="text" name="nick" placeholder="Nickname..." value="<?php echo(userData($con, $_GET["usr"])["nick"]); ?>"><br>*/?>
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
      } elseif ($_GET["error"] == "usrcreated") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Benutzer erstellt!</p>";
      }
    }?>
    </div>
       
       <?php
      } else {?>
        <div class="main">
          <a href="admin.php" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
          <h1 style="margin-top: 20px; font-size: 3rem">Benutzer Erstellen:</h1>
          <form action="includes/usermanager.inc.php" method="post">
            <input type="text" name="fullname" placeholder="Voller Name..." style="width: 500px;"><br>
            <input type="number" name="disabled" value="0" hidden="1">
            <?php
              if (getUserPower($con, $_SESSION["username"]) >= 110) {
                rolesList($con);
              }
            ?>
            <input type="checkbox" name="admin" checked="1" hidden="1">
            <button type="submit" name="create">Erstellen</button><br><br>
        <?php 
        if (isset($_GET["error"])) {
          if ($_GET["error"] == "emptyf") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Fülle bitte alle Felder!";
          } elseif ($_GET["error"] == "userexists") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Es gibt bereits einen User mit diesem Namen!</p>";
          }
        }?>
        </div>
        <?php
      }
    } elseif ($_GET["page"] == "roles") {
      if ((!isset($_GET["role"]) || roleData($con, $_GET["role"]) === false) && !isset($_GET["create"])) {
  ?>

        <div class='main'>
          <h1>Rollen</h1><br>
          <form action="admin.php">
            <input type="hidden" name="page" value="roles">
            <button type='submit' name='create'>Hinzufügen</button>
          </form>
          <?php
            roles($con);
          ?>
          <?php 
          if (isset($_GET["error"])) {
            if ($_GET["error"] == "del") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Rolle gelöscht!</p>";
              echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["name"]."</p>";
            } elseif ($_GET["error"] == "norole") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle gibt es nicht!</p>";
            } elseif ($_GET["error"] == "rolecreated") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Rolle Erstellt!</p>";
              echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["name"]."</p>";
            } elseif ($_GET["error"] == "protrole") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle ist vom System geschützt!</p>";
            }
          }?>
        </div>

    <?php
      } elseif (isset($_GET["role"])) {?>
        <div class="main">
          <a href="admin.php?page=roles" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
          <h1 style="margin-top: 20px; font-size: 3rem"><?php echo(roleData($con, $_GET["role"])["name"]); ?></h1>
            <form action="includes/rolemanager.inc.php" method="post">
              <input type="hidden" name="role" value="<?php echo($_GET["role"]); ?>">
              <input type="number" name="newh" placeholder="Höhe..." value="<?php echo($_GET["role"]); ?>" style="width: 500px;"><br>
              <input type="text" name="name" placeholder="Name..." value="<?php echo(roleData($con, $_GET["role"])["name"]); ?>"><br>
              <input type="number" name="power" placeholder="Power..." value="<?php echo(roleData($con, $_GET["role"])["power"]); ?>"><br>
              <button type="submit" name="edit" style="margin-bottom: 7px;">Bearbeiten</button><br>
              <button type="submit" name="del">Löschen</button><br><br>
            </form>
          <?php 
          if (isset($_GET["error"])) {
            if ($_GET["error"] == "editedrole") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Rolle bearbeitet!</p>";
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
        <?php
      } else {?>
        <div class="main">
          <a href="admin.php?page=roles" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
          <h1 style="margin-top: 20px; font-size: 3rem">Rolle Erstellen:</h1>
            <form action="includes/rolemanager.inc.php" method="post">
              <input type="number" name="role" placeholder="Höhe..." style="width: 500px;"><br>
              <input type="text" name="name" placeholder="Name..."><br>
              <input type="number" name="power" placeholder="Power..."><br>
              <button type="submit" name="create">Erstellen</button><br><br>
            </form>
          <?php 
          if (isset($_GET["error"])) {
            if ($_GET["error"] == "editedrole") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Rolle bearbeitet!</p>";
            } elseif ($_GET["error"] == "del") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Rolle gelöscht!</p>";
              echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["name"]."</p>";
            } elseif ($_GET["error"] == "norole") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle gibt es nicht!</p>";
            } elseif ($_GET["error"] == "roleexists") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Es gibt bereits eine Rolle mit dieser Höhe!</p>";
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
      <?php
      }
    } elseif ($_GET["page"] == "groups") {
      if ((!isset($_GET["gid"]) || groupDataById($con, $_GET["gid"]) === false) && !isset($_GET["create"])) {
        ?>
      
        <div class='main'>
          <h1>Gruppen</h1><br>
          <form action="admin.php">
            <input type="hidden" name="page" value="groups">
            <button type='submit' name='create'>Hinzufügen</button>
          </form>
          <?php
            groups($con);
          ?>
          <?php 
          if (isset($_GET["error"])) {
            if ($_GET["error"] == "del") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Gruppe gelöscht!</p>";
              echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["name"]."</p>";
            } elseif ($_GET["error"] == "norole") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Gruppe gibt es nicht!</p>";
            } elseif ($_GET["error"] == "protrole") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle ist vom System geschützt!</p>";
            }
          }?>
        </div>

    <?php
      } elseif (isset($_GET["gid"])) {?>
        <div class="main">
          <a href="admin.php?page=groups" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
          <h1 style="margin-top: 20px; font-size: 3rem"><?php echo(groupDataById($con, $_GET["gid"])["name"]); ?></h1>
            <form action="includes/groupmanager.inc.php" method="post">
              <input type="hidden" name="group" value="<?php echo(groupDataById($con, $_GET["gid"])["account"]); ?>">
              <input type="text" name="newacc" placeholder="Account..." value="<?php echo(groupDataById($con, $_GET["gid"])["account"]); ?>" style="width: 500px;"><br>
              <input type="text" name="name" placeholder="Name..." value="<?php echo(groupDataById($con, $_GET["gid"])["name"]); ?>"><br>
              <button type="submit" name="edit" style="margin-bottom: 7px;">Bearbeiten</button><br>
              <button type="submit" name="del">Löschen</button><br><br>
            </form>
          <?php 
          if (isset($_GET["error"])) {
            if ($_GET["error"] == "edited") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Gruppe bearbeitet!</p>";
            } elseif ($_GET["error"] == "created") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Gruppe Erstellt!</p>";
            } elseif ($_GET["error"] == "addedgrouper") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Benutzer zur Gruppe hinzufefügt!</p>";
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>".userData($con, $_GET["usr"])["fullname"]."</p>";
            } elseif ($_GET["error"] == "delgrouper") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Benutzer aus der Gruppe entfernt!</p>";
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>".userData($con, $_GET["usr"])["fullname"]."</p>";
            }
          }?>
          </div>
          <div class="main">
            <?php
              groupTable($con, $_GET["gid"]);
            ?>
            <br>
            <form action="includes/groupmanager.inc.php" method="post">
              <input type="hidden" name="group" value="<?php echo($_GET["gid"]); ?>">
              <?php
                userListNotInGroup($con, $_GET["gid"]);
              ?>
              <button type="submit" name="add" style="margin-bottom: 7px;">Hinzufügen</button><br>
            </form>
          </div>
        <?php
      } else {?>
        <div class="main">
          <a href="admin.php?page=groups" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
          <h1 style="margin-top: 20px; font-size: 3rem">Gruppe Erstellen:</h1>
            <form action="includes/groupmanager.inc.php" method="post">
              <input type="text" name="name" placeholder="Name..." style="width: 500px;"><br>
              <button type="submit" name="create">Erstellen</button><br><br>
            </form>
          <?php 
          if (isset($_GET["error"])) {
            if ($_GET["error"] == "exists") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Es gibt bereits eine Rolle 
              mit diesem Accountnamen!</p>";
            } elseif ($_GET["error"] == "emptyf") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Wichtige Felder waren leer!</p>";
            }
          }?>
          </div>
      <?php
      }
    } elseif ($_GET["page"] == "news") {?>

      <div class="main">
      <h1>Neuigkeiten</h1>
      <form action="includes/newsmanager.inc.php" method="post">
        <br><textarea name="news" id="news" placeholder="Sende den Benutzern Neuigkeiten und Informartionen!" cols="50" rows="20" style="color: #FFF; background: none;"></textarea><br><br>
        <button type="submit" name="add" style="margin-bottom: 7px;">Veröffentlichen!</button><br>
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
          <button type="submit" name="create">Erstellen</button><br><br>
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
