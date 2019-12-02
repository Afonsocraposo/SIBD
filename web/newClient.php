<?php
include("database.php");
$db = new Database();
$dbh = $db->connect();

?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>New Client</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            text-align: center;
        }
    </style>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            $("#birth_date").datepicker({
                minDate: "-120",
                maxDate: "+0",
                changeYear: true,
                changeMonth: true,
                yearRange: "-120:+0",
                dateFormat: "yy-mm-dd"
            });
        });
    </script>
</head>

<body>

    <?php

    echo "<div style='width: 100%; text-align:center'><button name='' value='' style='font-size:2em;' onclick=\"location.href='" . $db->url() . "clients.php'\">&#127968;</button><br></div><br>";


    $value_VAT = isset($_POST['VAT']) ? $_POST['VAT'] : "";
    $value_name = isset($_POST['name']) ? $_POST['name'] : "";
    $value_birth_date = isset($_POST['birth_date']) ? $_POST['birth_date'] : "";
    $value_street = isset($_POST['street']) ? $_POST['street'] : "";
    $value_city = isset($_POST['city']) ? $_POST['city'] : "";
    $value_zip = isset($_POST['zip']) ? $_POST['zip'] : "";
    $value_gender = isset($_POST['gender']) ? $_POST['gender'] : "";
    $value_phone = isset($_POST['phone']) ? $_POST['phone'] : "";

    if (!empty($value_VAT) && !empty($value_name) && !empty($value_birth_date) && !empty($value_street) && !empty($value_city) && !empty($value_zip) && !empty($value_gender)) {


        $query_client = "INSERT INTO client (VAT, name, birth_date, street, city, zip, gender, age)
        VALUES (?, ?, ?, ?, ?, ?, ?, TIMESTAMPDIFF(YEAR, ? , CURDATE()))";
        $query_phone = "INSERT INTO phone_number_client (VAT, phone)
        VALUES (?, ?)";
        $query_delete = "DELETE FROM client WHERE VAT=?";

        $stmt = $dbh->prepare($query_client);
        $stmt->bindParam(1, $value_VAT);
        $stmt->bindParam(2, $value_name);
        $stmt->bindParam(3, $value_birth_date);
        $stmt->bindParam(4, $value_street);
        $stmt->bindParam(5, $value_city);
        $stmt->bindParam(6, $value_zip);
        $stmt->bindParam(7, $value_gender);
        $stmt->bindParam(8, $value_birth_date);

        if (!$stmt->execute()) {
            print("Something went wrong when creating the client");
        } else {
            echo "New client created successfully<br>";
            $stmt = $dbh->prepare($query_phone);
            $stmt->bindParam(1, $value_VAT);
            $stmt->bindParam(2, $value_phone);
            if (!$stmt->execute()) {
                $stmt = $dbh->prepare($query_delete);
                $stmt->bindParam(1, $value_VAT);
                $stmt->execute();
                echo "Error: Something went wrong.";
            } else {
                echo "New phone created successfully<br>";
                echo "<script>location.href='" . $db->url() . "client.php?VAT=$value_VAT'</script>";
            }
            $stmt = null;
        }
    }
    $dbh = null;
    ?>

    <form action="newClient.php" method="post">
        <label for="VAT">VAT</label><br>
        <input name="VAT" id="VAT" type="text" placeholder="1234567890" inputmode="numeric" pattern="[0-9]{9,}$" maxlength="10" required="required" oninvalid="setCustomValidity('Invalid VAT')" oninput="setCustomValidity('')"><br>

        <br><label for="name">Name</label><br>
        <input name="name" id="name" type="text" placeholder="João Silva" pattern="[A-zÀ-ÿ ]+$" maxlength="64" required="required" oninvalid="setCustomValidity('Invalid name')" oninput="setCustomValidity('')"><br>

        <br><label for="birth_date">Date of Birth</label><br>
        <input name="birth_date" id="birth_date" required="required" oninvalid="setCustomValidity('Invalid Date')" oninput="setCustomValidity('')"><br>

        <br><label for="street">Street</label><br>
        <input name="street" id="street" type="text" placeholder="Rua dos Lírios, 1" maxlength="32" required="required" oninvalid="setCustomValidity('Invalid street')" oninput="setCustomValidity('')"><br>

        <br><label for="city">City</label><br>
        <input name="city" id="city" type="text" placeholder="Lisbon" pattern="[A-zÀ-ÿ ]+$" maxlength="32" required="required" oninvalid="setCustomValidity('Invalid city')" oninput="setCustomValidity('')"><br>

        <br><label for="zip">ZIP code</label><br>
        <input name="zip" id="zip" type="text" placeholder="1234-567" inputmode="numeric" pattern="[0-9-]{8,8}$" maxlength="8" onkeydown="if(this.value.length==4 && event.keyCode!=8) this.value=this.value+'-'" required="required" oninvalid="setCustomValidity('Invalid ZIP code')" oninput="setCustomValidity('')"><br>

        <br><label for="gender">Gender</label><br>
        <input name="gender" id="male" type="radio" value="Male" checked>
        <label for="male">Male</label>
        <input name="gender" id="female" type="radio" value="Female">
        <label for="female">Female</label><br>
        <br>

        <label for="phone">Phone Number</label><br>
        <input name="phone" id="phone" type="text" placeholder="912345678" inputmode="numeric" pattern="[0-9+]{9,}$" maxlength="9" required="required" oninvalid="setCustomValidity('Invalid Phone Number')" oninput="setCustomValidity('')"><br>
        <br>

        <input type="submit" value="New Client">
    </form>

    <script>

    </script>
    <div style="left:0;width:100%;height:20px;position:fixed;z-index:99;bottom:0;text-align:center">
        <span style="background:rgba(150,150,150,0.5)">SIBD - Project Part 3 - Group 50</span>
    </div>
</body>

</html>