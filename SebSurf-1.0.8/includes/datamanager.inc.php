<?php

session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';
$datac = isset($_POST["datac"]);
$page = "profile";
if ($datac) {
    $page = "datacenter";
}
if (isset($_POST["add"])) {
    if (empty($_POST["name"]) || empty($_POST["lessons"]) || empty($_POST["date"]) || $_POST["team"] == "null") {
        header("location: ../".$page.".php?error=emptyf");
        exit();
    }
    if (invalidName($_POST["name"])) {
        header("location: ../".$page.".php?error=invalid");
        exit();
    }
    createData($con, $_POST["name"], $_SESSION["username"], $_POST["lessons"], $_POST["date"], $_POST["team"]);
    updateUserLessons($con, $_SESSION["username"]);
} elseif (isset($_POST["del"])) {
    if (empty($_POST["id"])) {
        header("location: ../".$page.".php?error=eemptyf");
        exit();
    }
    $data = dataData($con, $_POST["id"]);
    if ($datac != "profile") {
        sendNotification($con, $data["account"], "root", "Event gelöscht!", "Dein Event '".$data["name"]."' wurde von '".$_SESSION["username"]."' gelöscht!");
    }
    updateUserLessons($con, $_SESSION["username"]);
    delData($con, $_POST["id"]);
    header("location: ../".$page.".php?error=deldata&name=".$data["name"]);
    exit();
} elseif (isset($_POST["edit"])) {
    if (empty($_POST["id"])) {
        header("location: ../".$page.".php?error=eemptyf");
        exit();
    }

    if (!empty($_POST["name"])) {
        editDataName($con, $_POST["id"], $_POST["name"]);
    }

    if (!empty($_POST["date"])) {
        editDataDate($con, $_POST["id"], $_POST["date"]);
    }

    if (!empty($_POST["lessons"])) {
        editDataDuration($con, $_POST["id"], $_POST["lessons"]);
    }

    if ($_POST["team"] !== "null") {
        editDataTeam($con, $_POST["id"], $_POST["team"]);
    }

    updateUserLessons($con, $_SESSION["username"]);
    $data = dataData($con, $_POST["id"]);
    if ($datac) {
        sendNotification($con, $data["account"], "root", "Event bearbeitet!", "Dein Event '".$data["name"]."' wurde von '".$_SESSION["username"]."' bearbeitet!");
    }
    header("location: ../".$page.".php?error=dataedited&name=".dataData($con, $_POST["id"])["name"]);
    exit();
}
else {
    header("location: ../".$page.".php?error=notfromsubmit");
}