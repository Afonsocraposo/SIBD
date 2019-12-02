<?php
include("database.php");
$db = new Database();
$dbh = $db->connect();

?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Appointments - Calendar</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="calendar.css" type="text/css" rel="stylesheet" />
    <style>

    </style>
</head>

<body>
    <?php
    include 'calendar.php';

    $value_VAT = isset($_POST['VAT']) ? $_POST['VAT'] : "";
    echo "<div style='width: 100%; text-align:center'>
    <button name='' value='' style='" . (empty($value_VAT) ? "visibility:hidden; " : "") . "font-size:2em;' onclick=\"location.href='" . $db->url() . "client.php?VAT=$value_VAT'\"><</button>
    <button name='' value='' style='font-size:2em;' onclick=\"location.href='" . $db->url() . "clients.php'\">&#127968;</button>
    <button name='' value='' style='visibility:hidden;font-size:2em;'><</button>
    </div><br>";

    $calendar = new Calendar();

    $calendar->setVAT($value_VAT);

    echo $calendar->show();


    $dbh = null;
    ?>
    <div style="left:0;width:100%;height:20px;position:fixed;z-index:99;bottom:0;text-align:center">
        <span style="background:rgba(150,150,150,0.5)">SIBD - Project Part 3 - Group 50</span>
    </div>
</body>

</html>