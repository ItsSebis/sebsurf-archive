<?php

session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (!isset($_POST["role"]) || $_POST["role"] == "null") {
    header("location: ../admin.php?error=norole&page=roles");
    exit();
}
$id = $_POST["role"];
if (roleData($con, $id)["createdby"] == "root") {
    header("location: ../admin.php?error=protrole&page=roles");
    exit();
}
if (getRole($con, $id) === false && !isset($_POST["create"])) {
    header("location: ../admin.php?error=norole&page=roles");
    exit();
}
$name = roleData($con, $id)["name"];
if (!empty($_POST["name"])) {
    $name = $_POST["name"];
}

if (roleData($con, $id)["power"] > getUserPower($con, $_SESSION["username"])) {
    header("location: ../admin.php?error=lesspower&page=roles");
    exit();
}

if (isset($_POST["edit"])) {

    $data = roleData($con, $id);
    if ($data["name"] != $name) {
        editRoleName($con, $id, $name);
        #logRoleEdit($con, "Edited roles name of role '".$data["name"]."' to '".$name."'!", $_SESSION["username"]);
    }

    if (!empty($_POST["power"])) {
        $power = $_POST["power"];
        if (roleData($con, $id)["power"] >= getUserPower($con, $_SESSION["username"])) {
            header("location: ../admin.php?error=lesspower&page=roles");
            exit();
        }
        if ($power >= getUserPower($con, $_SESSION["username"])) {
            header("location: ../admin.php?error=lesspower&page=roles");
            exit();
        }
        if ($power > 127) {
            header("location: ../admin.php?error=toohighpower&page=roles");
            exit();
        }
        editRolePower($con, $id, $power);
        #logRoleEdit($con, "Edited roles power of role '".$data["name"]."' to '".$power."'!", $_SESSION["username"]);
    }
    $_SESSION["admin"] = isAdmin($con, $_SESSION["username"]);

    header("location: ../admin.php?error=editedrole&page=roles&name=".$name);
    exit();
} elseif (isset($_POST["create"])) {
    if (getRole($con, $id) !== false) {
        header("location: ../admin.php?error=roleexists&page=roles");
        exit(); 
    }
    if (empty($_POST["name"]) || empty($_POST["power"])) {
        header("location: ../admin.php?error=emptyf&page=roles");
        exit();
    }

    $power = $_POST["power"];

    if ($power >= getUserPower($con, $_SESSION["username"])) {
        header("location: ../admin.php?error=lesspower&page=roles");
        exit();
    }

    createRole($con, $id, $name, $_SESSION["username"], $power);
    #logRoleEdit($con, "Created role '".$data["name"]."' with name '".$name."', power '".$power."' and id '".$id."'!", $_SESSION["username"]);

    header("location: ../admin.php?error=rolecreated&page=roles&name=".$name);
    exit();
} elseif (isset($_POST["del"])) {
    clearRole($con, $id);
    delRole($con, $id);
    #logRoleEdit($con, "Deleted role '".$data["name"]."'!", $_SESSION["username"]);
    header("location: ../admin.php?error=del&page=roles&name=".$name);
    exit();
}
