<?php
include("database.php");
$db = new Database();
$dbh = $db->connect();

?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>SIBD</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="calendar.css" type="text/css" rel="stylesheet" />
    <style>

    </style>
</head>

<body>
    <?php
    include 'calendar.php';
    echo "<div style='width: 100%; text-align:center'><button name='' value='' style='font-size:2em;' onclick=\"location.href='" . $db->url() . "clients.php'\">&#127968;</button><br></div><br>";

    $value_VAT = isset($_POST['VAT']) ? $_POST['VAT'] : "";

    $calendar = new Calendar();

    $calendar->setVAT($value_VAT);

    echo $calendar->show();


    $dbh = null;
    ?>

</body>

</html>