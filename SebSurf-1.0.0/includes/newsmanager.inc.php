<?php

session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (!isset($_POST["news"])) {
    header("location: ../?error=notfromsubmit");
    exit();
}

if (empty($_POST["news"]) && !isset($_POST["del"])) {
    header("location: ../admin.php?error=emptyf&page=news");
    exit();
}

if (isset($_POST["del"])) {
    deleteNews($con);
    header("location: ../admin.php?error=cleaned&page=news");
    exit();
}

$news = nl2br($_POST["news"]);

if (strlen($news) > 2000) {
    header("location: ../admin.php?error=toolong&page=news");
    exit();
}

editNews($con, $news);