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

        input {
            width: 100%;
        }

        textarea {
            width: 100%;
        }

        form {
            width: 100%;
        }

        #popup {
            width: 50%;
            padding: 20px;
            display: none;
            position: fixed;
            background-color: white;
            text-align: center;
            margin: auto;
            border-style: solid;
            top: 20%;
            left: 25%;
            border-width: 1px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
    </style>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script>
        function setValues(dic) {
            for (const [key, value] of Object.entries(dic)) {
                document.getElementById(key).value = value;
                if (key.substring(key.length - 4, key.length) == "desc") {
                    console.log(value);
                    if (value.length > 0) {
                        document.getElementById(key + "B").style.background = "#90EE90";
                    }
                }
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
        <button name='' value=''>&#128190;</button>
    </form>";
    ?>

    <div style="display: flex">
        <div style="width: 50%">
            <h3>Upper Right</h3>
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
                    <td><input maxlength="4" id="URtooth8" name="UR8" form="inputForm"></td>
                    <td><input maxlength="4" id="URtooth7" name="UR7" form="inputForm"></td>
                    <td><input maxlength="4" id="URtooth6" name="UR6" form="inputForm"></td>
                    <td><input maxlength="4" id="URtooth5" name="UR5" form="inputForm"></td>
                    <td><input maxlength="4" id="URtooth4" name="UR4" form="inputForm"></td>
                    <td><input maxlength="4" id="URtooth3" name="UR3" form="inputForm"></td>
                    <td><input maxlength="4" id="URtooth2" name="UR2" form="inputForm"></td>
                    <td><input maxlength="4" id="URtooth1" name="UR1" form="inputForm"></td>
                </tr>
                <tr>
                    <td>
                        <input style="display:none" maxlength="255" name="UR8desc" id="UR8desc" form="inputForm" value="">
                        <button id="UR8descB" onclick="popup('UR8desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UR7desc" id="UR7desc" form="inputForm" value="">
                        <button id="UR7descB" onclick="popup('UR7desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UR6desc" id="UR6desc" form="inputForm" value="">
                        <button id="UR6descB" onclick="popup('UR6desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UR5desc" id="UR5desc" form="inputForm" value="">
                        <button id="UR5descB" onclick="popup('UR5desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UR4desc" id="UR4desc" form="inputForm" value="">
                        <button id="UR4descB" onclick="popup('UR4desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UR3desc" id="UR3desc" form="inputForm" value="">
                        <button id="UR3descB" onclick="popup('UR3desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UR2desc" id="UR2desc" form="inputForm" value="">
                        <button id="UR2descB" onclick="popup('UR2desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UR1desc" id="UR1desc" form="inputForm" value="">
                        <button id="UR1descB" onclick="popup('UR1desc')">+</button>
                    </td>
                </tr>
            </table>
        </div>
        <div style="width: 50%">
            <h3>Upper Left</h3>
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
                    <td><input maxlength="4" id="ULtooth1" name="UL1" form="inputForm"></td>
                    <td><input maxlength="4" id="ULtooth2" name="UL2" form="inputForm"></td>
                    <td><input maxlength="4" id="ULtooth3" name="UL3" form="inputForm"></td>
                    <td><input maxlength="4" id="ULtooth4" name="UL4" form="inputForm"></td>
                    <td><input maxlength="4" id="ULtooth5" name="UL5" form="inputForm"></td>
                    <td><input maxlength="4" id="ULtooth6" name="UL6" form="inputForm"></td>
                    <td><input maxlength="4" id="ULtooth7" name="UL7" form="inputForm"></td>
                    <td><input maxlength="4" id="ULtooth8" name="UL8" form="inputForm"></td>
                </tr>
                <tr>
                    <td>
                        <input style="display:none" maxlength="255" name="UL1desc" id="UL1desc" form="inputForm" value="">
                        <button id="UL1descB" onclick="popup('UL1desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UL2desc" id="UL2desc" form="inputForm" value="">
                        <button id="UL2descB" onclick="popup('UL2desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UL3desc" id="UL3desc" form="inputForm" value="">
                        <button id="UL3descB" onclick="popup('UL3desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UL4desc" id="UL4desc" form="inputForm" value="">
                        <button id="UL4descB" onclick="popup('UL4desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UL5desc" id="UL5desc" form="inputForm" value="">
                        <button id="UL5descB" onclick="popup('UL5desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UL6desc" id="UL6desc" form="inputForm" value="">
                        <button id="UL6descB" onclick="popup('UL6desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UL7desc" id="UL7desc" form="inputForm" value="">
                        <button id="UL7descB" onclick="popup('UL7desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="UL8desc" id="UL8desc" form="inputForm" value="">
                        <button id="UL8descB" onclick="popup('UL8desc')">+</button>
                    </td>
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
                    <td>
                        <input style="display:none" maxlength="255" name="LR8desc" id="LR8desc" form="inputForm" value="">
                        <button id="LR8descB" onclick="popup('LR8desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LR7desc" id="LR7desc" form="inputForm" value="">
                        <button id="LR7descB" onclick="popup('LR7desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LR6desc" id="LR6desc" form="inputForm" value="">
                        <button id="LR6descB" onclick="popup('LR6desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LR5desc" id="LR5desc" form="inputForm" value="">
                        <button id="LR5descB" onclick="popup('LR5desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LR4desc" id="LR4desc" form="inputForm" value="">
                        <button id="LR4descB" onclick="popup('LR4desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LR3desc" id="LR3desc" form="inputForm" value="">
                        <button id="LR3descB" onclick="popup('LR3desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LR2desc" id="LR2desc" form="inputForm" value="">
                        <button id="LR2descB" onclick="popup('LR2desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LR1desc" id="LR1desc" form="inputForm" value="">
                        <button id="LR1descB" onclick="popup('LR1desc')">+</button>
                    </td>
                </tr>
                <tr>
                    <td><input maxlength="4" id="LRtooth8" name="LR8" form="inputForm"></td>
                    <td><input maxlength="4" id="LRtooth7" name="LR7" form="inputForm"></td>
                    <td><input maxlength="4" id="LRtooth6" name="LR6" form="inputForm"></td>
                    <td><input maxlength="4" id="LRtooth5" name="LR5" form="inputForm"></td>
                    <td><input maxlength="4" id="LRtooth4" name="LR4" form="inputForm"></td>
                    <td><input maxlength="4" id="LRtooth3" name="LR3" form="inputForm"></td>
                    <td><input maxlength="4" id="LRtooth2" name="LR2" form="inputForm"></td>
                    <td><input maxlength="4" id="LRtooth1" name="LR1" form="inputForm"></td>
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
            <h3>Lower Right</h3>
        </div>
        <div style="width: 50%">
            <table>
                <tr style="border-top: 1px solid #ddd;">
                    <td>
                        <input style="display:none" maxlength="255" name="LL1desc" id="LL1desc" form="inputForm" value="">
                        <button id="LL1descB" onclick="popup('LL1desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LL2desc" id="LL2desc" form="inputForm" value="">
                        <button id="LL2descB" onclick="popup('LL2desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LL3desc" id="LL3desc" form="inputForm" value="">
                        <button id="LL3descB" onclick="popup('LL3desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LL4desc" id="LL4desc" form="inputForm" value="">
                        <button id="LL4descB" onclick="popup('LL4desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LL5desc" id="LL5desc" form="inputForm" value="">
                        <button id="LL5descB" onclick="popup('LL5desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LL6desc" id="LL6desc" form="inputForm" value="">
                        <button id="LL6descB" onclick="popup('LL6desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LL7desc" id="LL7desc" form="inputForm" value="">
                        <button id="LL7descB" onclick="popup('LL7desc')">+</button>
                    </td>
                    <td>
                        <input style="display:none" maxlength="255" name="LL8desc" id="LL8desc" form="inputForm" value="">
                        <button id="LL8descB" onclick="popup('LL8desc')">+</button>
                    </td>
                </tr>
                <tr style="border-top: 1px solid #ddd;">
                    <td><input maxlength="4" id="LLtooth1" name="LL1" form="inputForm"></td>
                    <td><input maxlength="4" id="LLtooth2" name="LL2" form="inputForm"></td>
                    <td><input maxlength="4" id="LLtooth3" name="LL3" form="inputForm"></td>
                    <td><input maxlength="4" id="LLtooth4" name="LL4" form="inputForm"></td>
                    <td><input maxlength="4" id="LLtooth5" name="LL5" form="inputForm"></td>
                    <td><input maxlength="4" id="LLtooth6" name="LL6" form="inputForm"></td>
                    <td><input maxlength="4" id="LLtooth7" name="LL7" form="inputForm"></td>
                    <td><input maxlength="4" id="LLtooth8" name="LL8" form="inputForm"></td>
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
            <h3>Lower Left</h3>
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
                                $quadrant = "'LR";
                                break;
                            case 'Lower left':
                                $quadrant = "'LL";
                                break;
                            case 'Upper right':
                                $quadrant = "'UR";
                                break;
                            case 'Upper left':
                                $quadrant = "'UL";
                                break;
                            default:
                                $quadrant = "'";
                                break;
                        }
                        $dic = $dic . $quadrant . "tooth" . $chart["number"] . "': " . $chart["measure"] . ", ";
                        $dic = $dic . $quadrant . $chart["number"] . "desc': '" . $chart["description"] . "', ";
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

        <div id="popup">
            <div style="float:right"><button onclick="document.getElementById('popup').style.display = 'none';">X</button></div><br><br>
            <div>
                <h2 id="tooth"></h2>
                <label for="add_description">Description: </label>
                <textarea maxlength="255" wrap='hard' name='add_description' id="add_description"></textarea><br>
                <br>
                <div style="width: 100%; text-align: center">
                    <button id="saveDescr" value='test'>SUBMIT</button>
                </div>
            </div>
        </div>

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
                        x: 90 * scale,
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

            function popup(id) {
                document.getElementById("popup").style.display = "block";
                document.getElementById("tooth").innerHTML = id.substring(0, 3);
                console.log(id)
                document.getElementById("add_description").value = document.getElementById(id).value;
                var old_element = document.getElementById("saveDescr");
                var new_element = old_element.cloneNode(true);
                old_element.parentNode.replaceChild(new_element, old_element);
                document.getElementById("saveDescr").addEventListener("click", function() {
                    if (document.getElementById("add_description").value.length > 0) {
                        document.getElementById(id + "B").style.background = "#90EE90";
                    } else {
                        document.getElementById(id + "B").style.background = "white";
                    }
                    document.getElementById(id).value = document.getElementById("add_description").value;
                    document.getElementById('popup').style.display = 'none';
                }, false);
            }

            quadrant("LL");
            quadrant("LR");
            quadrant("UL");
            quadrant("UR");
        </script>
</body>

</html>