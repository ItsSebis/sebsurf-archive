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
        <button type='submit' name='teams'>Teams<?php if (getAllRequestsCount($con) != 0) {echo(" <span style='color: black; border: solid red; border-radius: 15px; background-color: red'>".getAllRequestsCount($con)."</span>");} ?></button>
        <button type='submit' name='news'>Neuigkeiten</button>
        <?php
          if (getUserPower($con, $_SESSION["username"]) >= 115) {
        ?>
        <button type='submit' name='updates'>Server Updates</button><?php }?>
        <br><br>
        <button type='submit' name='adminlogout' style='border-color: red;'>Logout Admin</button>
      </form>
    </div>
    <?php 
      if (!isset($_GET["page"]) || $_GET["page"] == "users") {
    ?>

    <div class='main'>

    <h1>Users</h1>
    <table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
    <thead>
      <tr>
        <th style="padding-left: 10px; padding-right: 10px;">Account</th>
        <th style="padding-left: 10px; padding-right: 10px;">Nickname</th>
        <th style="padding-left: 10px; padding-right: 10px;">Role</th>
        <th style="padding-left: 10px; padding-right: 10px;">Active</th>
      </tr>
    </thead>
    <tbody><br>
    <?php
      users($con);
    ?>
    </tbody>
    </table>
    </div>

    <div class="main">
      <form action="includes/usermanager.inc.php" method="post">
        <input type="text" name="user" placeholder="Account..." style="width: 500px;"><br>
        <input type="text" name="nick" placeholder="Nickname..."><br>
        <input type="text" name="pw" placeholder="Password..."><br>
        <select name="disabled" id="disabled" style="background-color: #303030; outline: none; color: white; border: solid #333333; border-radius: 24px; width: 200px; height: 70px; padding: 14px 10px; transition: 0.2s; font-size: larger;">
          <option value="0">Active</option>
          <option value="1">Inactive</option>
        </select><br>
        <?php 
          if (getUserPower($con, $_SESSION["username"]) >= 110) {
            rolesList($con);
          }
        ?>
        <input type="checkbox" name="admin" checked="1" hidden="1">
        <button type="submit" name="edit" style="margin-bottom: 7px;">Edit User</button><br>
        <button type="submit" name="create" style="margin-bottom: 7px;">Create User</button><br>
        <button type="submit" name="del">Delete User</button><br><br>
      </form>
    <?php 
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "nopassword") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>No Password given!";
      } elseif ($_GET["error"] == "delself") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>You cannot delete <u style='color: red;'>yourself</u>!";
      } elseif ($_GET["error"] == "lesspower") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>You need more power to do this!</p>!";
      } elseif ($_GET["error"] == "delusr") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Deleted User!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["acc"]."</p>";
      } elseif ($_GET["error"] == "nouser") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>This user does not exist!";
      } elseif ($_GET["error"] == "norole") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>This role does not exist!";
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>ID: ".$_GET["id"]."</p>";
      } elseif ($_GET["error"] == "userexists") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>This user already exists!";
      } elseif ($_GET["error"] == "usercreated") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Created User!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["name"]."</p>";
      } elseif ($_GET["error"] == "usredited") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>User edited!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["name"]."</p>";
      } elseif ($_GET["error"] == "charlimitreached") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Maximun lenght of account name characters is 64!";
      } elseif ($_GET["error"] == "systemroot") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>You cannot edit or delete root!</p>!";
      }
    }?>
    </div>
  </div>

  <?php
    } elseif ($_GET["page"] == "roles") {
  ?>

<div class='main'>
  <h1>Roles</h1>
  <table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
  <thead>
    <tr>
      <th style="padding-left: 10px; padding-right: 10px;">id</th>
      <th style="padding-left: 10px; padding-right: 10px;">Name</th>
      <th style="padding-left: 10px; padding-right: 10px;">Created by</th>
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
        <input type="number" name="id" placeholder="Id..." style="width: 500px;"><br>
        <input type="text" name="name" placeholder="Name..."><br>
        <input type="number" name="power" placeholder="Set Power..."><br>
        <button type="submit" name="edit" style="margin-bottom: 7px;">Edit Role</button><br>
        <button type="submit" name="create" style="margin-bottom: 7px;">Create Role</button><br>
        <button type="submit" name="del">Delete Role</button><br><br>
      </form>
    <?php 
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "editedrole") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Edited role!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["name"]."</p>";
      } elseif ($_GET["error"] == "del") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Deleted Role!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["name"]."</p>";
      } elseif ($_GET["error"] == "norole") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>This role does not exist!";
      } elseif ($_GET["error"] == "roleexists") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>This role already exists!";
      } elseif ($_GET["error"] == "rolecreated") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Created Role!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>".$_GET["name"]."</p>";
      } elseif ($_GET["error"] == "emptyf") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Important fields were left empty!</p>";
      } elseif ($_GET["error"] == "lesspower") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>You need more power to do this!</p>!";
      } elseif ($_GET["error"] == "protrole") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>This is a system protected role!";
      } elseif ($_GET["error"] == "toohighpower") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Maximum power is 127!";
      }
    }?>
    </div>
  </div>
</div>

    <?php
    } elseif ($_GET["page"] == "updates") {
      if (getUserPower($con, $_SESSION["username"]) < 115) {
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
        <button type="submit" name="add" style="margin-bottom: 7px;">Publish!</button><br>
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
        <button type="submit" name="add" style="margin-bottom: 7px;">Publish!</button><br>
        <button type="submit" name="del" style="margin-bottom: 7px;">Clean</button><br>
      </form>
      <?php 
      if (isset($_GET["error"])) {
        if ($_GET["error"] == "edited") {
          echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Published news!</p>";
        } elseif ($_GET["error"] == "toolong") {
          echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>The maximum amount of characters is 2000!";
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
        <h1>Team-Management</h1>
        <?php
          teams($con);
        ?>
      </div>

      <div class="main">
        <h1>Team Requests</h1>
        <?php
          allTeamRequests($con);
        ?>
        <?php 
        if (isset($_GET["error"])) {
          if ($_GET["error"] == "accepted") {
            echo "<br><p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Accepted Teamrequest for Team '".$_GET['team']."'!</p>";
          } elseif ($_GET["error"] == "denied") {
            echo "<br><p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Denied Teamrequest for Team '".$_GET['team']."'!</p>";
          }
        }?>
      </div>

      <div class="main">
        <form action="includes/teammanager.inc.php" method="post">
          <input type="text" name="name" id="team" placeholder="Team Name..." style="width: 500px;"><br>
          <?php
            teamsList($con);
          ?>
          <button type="submit" name="create">Create</button><br><br>
          <button type="submit" name="delete">Delete</button>
        </form>
        <?php 
        if (isset($_GET["error"])) {
          if ($_GET["error"] == "c") {
            echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Created Team '".$_GET['team']."'!</p>";
          } elseif ($_GET["error"] == "toolong") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>The maximum amount of characters is 64!";
          } elseif ($_GET["error"] == "emptyf") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Please fill in all fields!";
          } elseif ($_GET["error"] == "del") {
            echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Deleted Team '".$_GET['team']."'!</p>";
          }
        }?>
      </div>

<?php
    }
} else {
  #####################################################################################################
      ?>
        <form class="log-in" method="post" action="" id="login_form">
            <h1>LOGIN TO PROCEED</h1>
            <p style="color: red; text-transform: uppercase; font-weight: 900;">Do not show this to public!</p>
            <input type="password" name="pass" placeholder="Enter Password!">
            <button type="submit" name="submit_pass">SUBMIT</button>
            <p>
                <?php echo "<br><p style='color: red;'>".$error."</p>"; ?>
            </p>
        </form>
        <?php
}
?>

  </body>
</html>
