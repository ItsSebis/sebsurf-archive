<?php

session_start();

require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["savenote"])) {
    if (strlen($_POST["note"]) > 2000) {
        header("location: ../profile.php?error=inboundoutofcharacter");
        exit();
    }
    setUserNote($con, $_SESSION["username"], $_POST["note"]);
    header("location: ../profile.php?error=notesaved");
    exit();
}
if (isset($_POST["setnick"])) {
    if (strlen($_POST["nick"]) > 22) {
        header("location: ../settings.php?error=inboundoutofcharacter");
        exit();
    }
    if (invalidNick($_POST["nick"])) {
        header("location: ../settings.php?error=invalid");
        exit();
    }
    setUserNick($con, $_SESSION["username"], $_POST["nick"]);
}

if (isset($_POST["setpw"])) {
    $pwHashed = userData($con, $_SESSION["username"])["usrpw"];
    $checkPw = password_verify($_POST["oldpw"], $pwHashed);
    if ($checkPw === false) {
        header("location: ../settings.php?error=wrongpw");
        exit();
    }
    $admin = false;
    if (empty($_POST["rpw"]) || empty($_POST["pw"])) {
        header("location: ../settings.php?error=repeatpw");
        exit();
    }
    if (pwMatch($_POST["pw"], $_POST["rpw"])) {
        header("location: ../settings.php?error=invalidrpw");
        exit();
    }
    $pw = $_POST["pw"];
    setPw($con, $_SESSION["username"], $pw, $admin);
    exit();
}

if ($_POST["user"] == $_SESSION["username"]) {
    $you = true;
} else {
    $you = false;
}
$user = $_POST["user"];

if (userData($con, $user) === false && !isset($_POST["create"])) {
    header("location: ../admin.php?error=nouser");
    exit();
} elseif (userData($con, $user) !== false && isset($_POST["create"])) {
    header("location: ../admin.php?error=userexists");
    exit();
}

if (getUserPower($con, $user) > getUserPower($con, $_SESSION["username"]) || $user == "root") {
    if ($user == "root") {
        header("location: ../admin.php?error=systemroot");
        exit();
    }
    header("location: ../admin.php?error=lesspower");
    exit();
}
if (isset($_POST["edit"])) {
    $admin = isset($_POST["admin"]);
    if ($admin == false) {
        if (empty($_POST["rpw"]) || empty($_POST["pw"])) {
            header("location: ../settings.php?error=repeatpw");
            exit();
        }
        if (pwMatch($_POST["pw"], $_POST["rpw"])) {
            header("location: ../settings.php?error=invalidrpw");
            exit();
        }
    }
    $pw = $_POST["pw"];

    if (!empty($pw)) {
        setPw($con, $user, $pw, $admin);
        if (!$admin) {
            header("location: ../settings.php?error=pwset");
            exit();
        }
    } elseif (!$admin) {
        header("location: ../settings.php?error=nopassword");
        exit();
    }

    if ($admin && $_POST["role"] !== "null" && !$you) {
        $roleid = $_POST["role"];
        if (roleData($con, $roleid) == false) {
            header("location: ../admin.php?error=norole&id=".$roleid);
            exit();
        }
        if (roleData($con, $roleid)["power"] >= getUserPower($con, $_SESSION["username"]) && getUserPower($con, $_SESSION["username"]) < 126) {
            header("location: ../admin.php?error=lesspower");
            exit();
        }
        editUserRole($con, $user, $roleid);
    }

    if (!empty($_POST["nick"]) && strlen($_POST["nick"]) <= 22) {
        if ($_POST["nick"] == "_") {
            setUserNickAdmin($con, $user, "");
        } else {
            setUserNickAdmin($con, $user, $_POST["nick"]);
        }
    }

    if (!$you) {
        $disabled = $_POST["disabled"];
        setUserDisabled($con, $user, $disabled);
    }

    header("location: ../admin.php?error=usredited&name=".$user);
    exit();
} elseif (isset($_POST["del"])) {
    if ($you) {
        header("location: ../admin.php?error=delself");
        exit();
    }
    if (getUserPower($con, $user) >= getUserPower($con, $_SESSION["username"]) && getUserPower($con, $_SESSION["username"]) < 128) {
        header("location: ../admin.php?error=lesspower");
        exit();
    }
    $qry = "DELETE FROM users WHERE account=?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
      header("location: ../index.php?error=1");
      exit();
    }
  
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
  
    mysqli_stmt_close($stmt);

    header("location: ../admin.php?error=delusr&acc=".$user);
    exit();
} elseif (isset($_POST["create"])) {
    createUser($con, $user, $_SESSION["username"]);
}

header("location: ../index.php?error=1");
exit;