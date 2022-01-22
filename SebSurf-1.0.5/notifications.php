<?php
include_once "header.php";

if (isset($_POST["send"])) {
    $user = $_POST["user"];
    $subject = $_POST["subject"];
    $text = $_POST["text"];

    if (empty($user) || empty($subject) || empty($text)) {
        header("location: notifications.php?notify=create&error=emptyf");
        exit();
    }

    sendNotification($con, $user, $_SESSION["username"], $subject, $text);
    header("location: notifications.php?usr=".$user."&error=send");
    exit();
}

if (empty($_SESSION["username"])) {
    header("location: ./log-in.php");
    exit();
}
if (!isset($_GET["notify"])) {
?>
<script type="text/javascript">
    document.getElementById("notifies").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
</script>
<div class="main">
    <h1>Mitteilungen:</h1><br>
    <form action="notifications.php">
        <button type='submit' name='notify' value="create">Senden</button>
    </form>
    <?php
        notifyTable($con, $_SESSION["username"]);
        #readAllNotifications($con, $_SESSION["username"]);
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "send") {
                echo "<p style='color: lime; border: solid green; max-width: 500px; text-align: center; margin: 10px auto; border-radius: 7px;'>Es wurde erfolgreich ein Nachricht an ".userData($con, $_GET["usr"])["fullname"]." gesendet!</p>";
            }
        }
    ?>
</div>
<?php
} elseif ($_GET["notify"] == "create") {?>

    <div class="main">
        <h1 style="font-size: 1.5rem; margin-bottom: 10px;">Nachricht verfassen:</h1>
        <form action="notifications.php" method="post">
            <label for="users" style="font-size: larger;">Empfänger</label>
            <?php 
                userList($con);
            ?>
            <label for="subject" style="font-size: larger;">Betreff</label>
            <input type="text" name="subject" placeholder="Betreff" id="subject">
            <br><textarea name="text" id="text" placeholder="Hallo..." cols="100" rows="40" style="color: #FFF; background: none; text-align: left; 
            border: 2px solid black; border-radius: 24px; padding: 12px;"></textarea><br><br>
            <button type="submit" name="send" style="margin-bottom: 7px; color: lime;">Senden!</button>
        </form>
        <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "emptyf") {
                    echo "<p style='color: red; border: solid red; max-width: 500px; text-align: center; margin: 10px auto; border-radius: 7px;'>Fülle bitte alle Felder!</p>";
                }
            }
        ?>
    </div>

<?php
} else {
    $notifyid = $_GET["notify"];
    if (notifyData($con, $notifyid)["usr"] != $_SESSION["username"]) {
        header("location: ./notifications.php");
        exit();
    }
    notification($con, $notifyid);
}?>
