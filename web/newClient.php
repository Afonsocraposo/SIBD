<?php
include("database.php");
$db = new Database();
$mysqli = $db->connect();

?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>SIBD</title>
</head>

<body>
    <form action="newClient.php" method="post">
        <label for="VAT">VAT</label><br>
        <input name="VAT" id="VAT" type="text" placeholder="1234567890" inputmode="numeric" pattern="[0-9]{9,}$" maxlength="10" required="required" oninvalid="setCustomValidity('Invalid VAT')" oninput="setCustomValidity('')"><br>

        <br><label for="name">Name</label><br>
        <input name="name" id="name" type="text" placeholder="João Silva" pattern="[A-zÀ-ÿ ]+$" maxlength="64" required="required" oninvalid="setCustomValidity('Invalid name')" oninput="setCustomValidity('')"><br>

        <br><label for="birth_date">Date of Birth</label><br>
        <input name="birth_date" id="birth_date" type="date" required="required" min="1900-01-01" max=<?php
                                                                                                        echo date('Y-m-d');
                                                                                                        ?> oninvalid="setCustomValidity('Invalid Date')" oninput="setCustomValidity('')"><br>

        <br><label for=" street">Street</label><br>
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

    <?php
    $value_VAT = $_POST['VAT'];
    $value_name = $_POST['name'];
    $value_birth_date = $_POST['birth_date'];
    $value_street = $_POST['street'];
    $value_city = $_POST['city'];
    $value_zip = $_POST['zip'];
    $value_gender = $_POST['gender'];
    $value_phone = $_POST['phone'];

    if (!empty($value_VAT) && !empty($value_name) && !empty($value_birth_date) && !empty($value_street) && !empty($value_city) && !empty($value_zip) && !empty($value_gender)) {


        $query_client = "INSERT INTO client (VAT, name, birth_date, street, city, zip, gender, age)
        VALUES (?, ?, ?, ?, ?, ?, ?, TIMESTAMPDIFF(YEAR, ? , CURDATE()))";
        $query_phone = "INSERT INTO phone_number_client (VAT, phone)
        VALUES (?, ?)";
        $query_delete = "DELETE FROM client WHERE VAT=?";

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query_client);
        $stmt->bind_param('ssssssss', $value_VAT, $value_name, $value_birth_date, $value_street, $value_city, $value_zip, $value_gender, $value_birth_date);

        if (!$stmt->execute()) {
            print("Something went wrong when creating the client");
        } else {
            echo "New client created successfully<br>";
            $stmt->prepare($query_phone);
            $stmt->bind_param('ss', $value_VAT, $value_phone);
            if (!$stmt->execute()) {
                $stmt->prepare($query_delete);
                $stmt->bind_param('s', $value_VAT);
                echo "Error: Something went wrong.";
            } else {
                echo "New phone created successfully<br>";
                echo "<script>location.href='" . $db->url() . "client.php?VAT=" . $value_VAT . "'</script>";
            }
        }
    }

    $mysqli->close();
    ?>

</body>

</html>