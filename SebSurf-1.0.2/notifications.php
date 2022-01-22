    <?php
    include_once "header.php";
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
        <?php
            notifyTable($con, $_SESSION["username"]);
            #readAllNotifications($con, $_SESSION["username"]);
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
