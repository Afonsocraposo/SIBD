<?php
include("database.php");
$db = new Database();
$dbh = $db->connect();
?>
<html>

<head>
    <script src="https://unpkg.com/konva@4.0.18/konva.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>SIBD</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            text-align: center;
        }

        tr:hover {
            background: #fff;
        }
    </style>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script>
        function setValues(dic) {
            console.log(dic);
            for (const [key, value] of Object.entries(dic)) {
                document.getElementById(key).value = value;
            }
        }
    </script>
</head>

<body>
    <?php

    $value_VAT = isset($_GET['VAT']) ? $_GET['VAT'] : "";
    $value_timestamp = isset($_GET['timestamp']) ? $_GET['timestamp'] : "";

    echo "<div style='width: 100%; text-align:center'>
    <button name='' value='' style='font-size:2em;' onclick=\"location.href='" . $db->url() . "consultation.php?VAT=$value_VAT&timestamp=$value_timestamp'\"><</button>
    <button name='' value='' style='font-size:2em;' onclick=\"location.href='" . $db->url() . "clients.php'\">&#127968;</button>
    <button name='' value='' style='visibility:hidden;font-size:2em;'><</button>
    </div><br>";

    echo "<form id='inputForm' action='consultation.php?VAT=$value_VAT&timestamp=$value_timestamp' method='post'>
        <input name='charting' value='1' style='display:none'>
        <input type='submit' value='Save'>
    </form>";
    ?>

    <div style="display: flex">
        <div style="width: 50%">
            <h3>UR</h3>
            <table>
                <tr class='header' style="border-top: 1px solid #ddd;">
                    <td>
                        <h4>8</h4>
                    </td>
                    <td>
                        <h4>7</h4>
                    </td>
                    <td>
                        <h4>6</h4>
                    </td>
                    <td>
                        <h4>5</h4>
                    </td>
                    <td>
                        <h4>4</h4>
                    </td>
                    <td>
                        <h4>3</h4>
                    </td>
                    <td>
                        <h4>2</h4>
                    </td>
                    <td>
                        <h4>1</h4>
                    </td>
                </tr>
                <tr>
                    <td><input size="4" maxlength="4" id="URtooth8" name="UR8" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="URtooth7" name="UR7" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="URtooth6" name="UR6" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="URtooth5" name="UR5" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="URtooth4" name="UR4" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="URtooth3" name="UR3" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="URtooth2" name="UR2" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="URtooth1" name="UR1" form="inputForm"></td>
                </tr>
            </table>
        </div>
        <div style="width: 50%">
            <h3>UL</h3>
            <table>
                <tr class='header' style="border-top: 1px solid #ddd;">
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                </tr>
                <tr>
                    <td><input size="4" maxlength="4" id="ULtooth1" name="UL1" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="ULtooth2" name="UL2" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="ULtooth3" name="UL3" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="ULtooth4" name="UL4" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="ULtooth5" name="UL5" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="ULtooth6" name="UL6" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="ULtooth7" name="UL7" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="ULtooth8" name="UL8" form="inputForm"></td>
                </tr>
            </table>
        </div>
    </div>


    <div id="upper" style="display: flex">
        <div id="URquadrant"></div>
        <div id="ULquadrant"></div>
    </div>
    <div id="lower" style="display: flex">
        <div id="LRquadrant"></div>
        <div id="LLquadrant"></div>
    </div>


    <div style="display: flex">
        <div style="width: 50%">
            <table>
                <tr style="border-top: 1px solid #ddd;">
                    <td><input size="4" maxlength="4" id="LRtooth8" name="LR8" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LRtooth7" name="LR7" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LRtooth6" name="LR6" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LRtooth5" name="LR5" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LRtooth4" name="LR4" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LRtooth3" name="LR3" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LRtooth2" name="LR2" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LRtooth1" name="LR1" form="inputForm"></td>
                </tr>
                <tr class='header'>
                    <td>8</td>
                    <td>7</td>
                    <td>6</td>
                    <td>5</td>
                    <td>4</td>
                    <td>3</td>
                    <td>2</td>
                    <td>1</td>
                </tr>
            </table>
            <h3>LR</h3>
        </div>
        <div style="width: 50%">
            <table>
                <tr style="border-top: 1px solid #ddd;">
                    <td><input size="4" maxlength="4" id="LLtooth1" name="LL1" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LLtooth2" name="LL2" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LLtooth3" name="LL3" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LLtooth4" name="LL4" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LLtooth5" name="LL5" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LLtooth6" name="LL6" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LLtooth7" name="LL7" form="inputForm"></td>
                    <td><input size="4" maxlength="4" id="LLtooth8" name="LL8" form="inputForm"></td>
                </tr>
                <tr class='header'>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                </tr>
            </table>
            <h3>LL</h3>
        </div>
    </div>


    <?php
    if (!empty($value_VAT) && !empty($value_timestamp)) {

        $result_charting = null;

        $query_procedure_charting = "SELECT *
        FROM procedure_charting
        WHERE VAT=?
        AND date_timestamp=?";

        $stmt = $dbh->prepare($query_procedure_charting);
        $stmt->bindParam(1, $value_VAT);
        $stmt->bindParam(2, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the charting procedure");
        } else {
            if ($stmt->rowCount() > 0) {
                $result_charting = $stmt->fetchAll();
                if ($result_charting != null) {
                    $dic = "{";
                    foreach ($result_charting as &$chart) {
                        switch ($chart['quadrant']) {
                            case 'Lower right':
                                $dic = $dic . "'LR";
                                break;
                            case 'Lower left':
                                $dic = $dic . "'LL";
                                break;
                            case 'Upper right':
                                $dic = $dic . "'UR";
                                break;
                            case 'Upper left':
                                $dic = $dic . "'UL";
                                break;
                            default:
                                break;
                        }
                        $dic = $dic . "tooth" . $chart["number"] . "': " . $chart["measure"] . ", ";
                    }
                    $dic = $dic . "}";
                    echo "<script>
        setValues($dic);
    </script>";
                }
            }
        }
    } else {
        echo "<script>
        location.href = '" . $db->url() . "clients.php'
    </script>";
    }
    ?>

        <script>
            function quadrant(quadrant) {
                var width = window.innerWidth / 2;
                var scale = width / 600;
                var height = 180 * scale;

                var scaleX, x, scaleY, y;
                switch (quadrant) {
                    case 'LR':
                        scaleX = -1;
                        scaleY = 1;
                        x = width;
                        y = 0;
                        break;
                    case 'UL':
                        scaleX = 1;
                        scaleY = -1;
                        x = 0;
                        y = height;
                        break;
                    case 'UR':
                        scaleX = -1;
                        scaleY = -1;
                        x = width;
                        y = height;
                        break;
                    default:
                        scaleX = 1;
                        scaleY = 1;
                        x = 0;
                        y = 0;
                }

                var stage = new Konva.Stage({
                    container: quadrant + 'quadrant',
                    width: width,
                    height: height
                });

                var layer = new Konva.Layer({
                    scaleX: scaleX,
                    scaleY: scaleY,
                    x: x,
                    y: y
                });
                stage.add(layer);
                layer.on('mouseover', function(evt) {
                    var shape = evt.target;
                    shape.scaleX(scale * 1.2);
                    shape.scaleY(scale * 1.2);
                    layer.draw();
                });
                layer.on('mouseout', function(evt) {
                    var shape = evt.target;
                    shape.scaleX(scale);
                    shape.scaleY(scale);
                    layer.draw();
                });

                layer.on('click', function(evt) {
                    $("#" + evt.target.attrs.id).focus();
                });

                var tooth1 = new Image();
                tooth1.onload = function() {
                    var image = new Konva.Image({
                        x: 30 * scale,
                        y: this.height / 2 + 15,
                        image: tooth1,
                        offsetX: this.width / 2,
                        offsetY: this.height / 2,
                        width: this.naturalWidth * 2,
                        height: this.naturalHeight * 2,
                        id: quadrant + "tooth1",
                        scaleX: scale,
                        scaleY: scale
                    });
                    layer.add(image);
                    layer.batchDraw();
                };
                tooth1.src = 'http://web.tecnico.ulisboa.pt/ist425108/SIBD/images/teeth/tooth1.png';

                var tooth2 = new Image();
                tooth2.onload = function() {
                    var image = new Konva.Image({
                        x: 90,
                        y: this.height / 2 + 15,
                        image: tooth2,
                        offsetX: this.width / 2,
                        offsetY: this.height / 2,
                        width: this.naturalWidth * 2,
                        height: this.naturalHeight * 2,
                        id: quadrant + "tooth2",
                        scaleX: scale,
                        scaleY: scale
                    });
                    layer.add(image);
                    layer.batchDraw();
                };
                tooth2.src = 'http://web.tecnico.ulisboa.pt/ist425108/SIBD/images/teeth/tooth2.png';

                var tooth3 = new Image();
                tooth3.onload = function() {
                    var image = new Konva.Image({
                        x: 150 * scale,
                        y: this.height / 2 + 15,
                        image: tooth3,
                        offsetX: this.width / 2,
                        offsetY: this.height / 2,
                        width: this.naturalWidth * 2,
                        height: this.naturalHeight * 2,
                        id: quadrant + "tooth3",
                        scaleX: scale,
                        scaleY: scale
                    });
                    layer.add(image);
                    layer.batchDraw();
                };
                tooth3.src = 'http://web.tecnico.ulisboa.pt/ist425108/SIBD/images/teeth/tooth3.png';

                var tooth4 = new Image();
                tooth4.onload = function() {
                    var image = new Konva.Image({
                        x: 210 * scale,
                        y: this.height / 2 + 15,
                        image: tooth4,
                        offsetX: this.width / 2,
                        offsetY: this.height / 2,
                        width: this.naturalWidth * 2,
                        height: this.naturalHeight * 2,
                        id: quadrant + "tooth4",
                        scaleX: scale,
                        scaleY: scale
                    });
                    layer.add(image);
                    layer.batchDraw();
                };
                tooth4.src = 'http://web.tecnico.ulisboa.pt/ist425108/SIBD/images/teeth/tooth4.png';

                var tooth5 = new Image();
                tooth5.onload = function() {
                    var image = new Konva.Image({
                        x: 270 * scale,
                        y: this.height / 2 + 15,
                        image: tooth5,
                        offsetX: this.width / 2,
                        offsetY: this.height / 2,
                        width: this.naturalWidth * 2,
                        height: this.naturalHeight * 2,
                        id: quadrant + "tooth5",
                        scaleX: scale,
                        scaleY: scale
                    });
                    layer.add(image);
                    layer.batchDraw();
                };
                tooth5.src = 'http://web.tecnico.ulisboa.pt/ist425108/SIBD/images/teeth/tooth5.png';

                var tooth6 = new Image();
                tooth6.onload = function() {
                    var image = new Konva.Image({
                        x: 335 * scale,
                        y: this.height / 2 + 15,
                        image: tooth6,
                        offsetX: this.width / 2,
                        offsetY: this.height / 2,
                        width: this.naturalWidth * 2,
                        height: this.naturalHeight * 2,
                        id: quadrant + "tooth6",
                        scaleX: scale,
                        scaleY: scale
                    });
                    layer.add(image);
                    layer.batchDraw();
                };
                tooth6.src = 'http://web.tecnico.ulisboa.pt/ist425108/SIBD/images/teeth/tooth6.png';

                var tooth7 = new Image();
                tooth7.onload = function() {
                    var image = new Konva.Image({
                        x: 430 * scale,
                        y: this.height / 2 + 15,
                        image: tooth7,
                        offsetX: this.width / 2,
                        offsetY: this.height / 2,
                        width: this.naturalWidth * 2,
                        height: this.naturalHeight * 2,
                        id: quadrant + "tooth7",
                        scaleX: scale,
                        scaleY: scale
                    });
                    layer.add(image);
                    layer.batchDraw();
                };
                tooth7.src = 'http://web.tecnico.ulisboa.pt/ist425108/SIBD/images/teeth/tooth7.png';

                var tooth8 = new Image();
                tooth8.onload = function() {
                    var image = new Konva.Image({
                        x: 525 * scale,
                        y: this.height / 2 + 15,
                        image: tooth8,
                        offsetX: this.width / 2,
                        offsetY: this.height / 2,
                        width: this.naturalWidth * 2,
                        height: this.naturalHeight * 2,
                        id: quadrant + "tooth8",
                        scaleX: scale,
                        scaleY: scale
                    });
                    layer.add(image);
                    layer.batchDraw();
                };
                tooth8.src = 'http://web.tecnico.ulisboa.pt/ist425108/SIBD/images/teeth/tooth8.png';

            }

            quadrant("LL");
            quadrant("LR");
            quadrant("UL");
            quadrant("UR");
        </script>
</body>

</html>