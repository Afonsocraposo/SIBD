<?php
$host = "db.ist.utl.pt";
$user = "ist425108";
$pass = "skqy1678";
$name = "ist425108";

$conn = new mysqli($host, $user, $pass, $name);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>
<html>

<head>
    <title>SIBD</title>
</head>

<body>
    <form action="newClient.php" method="post">
        <label for="VAT">VAT</label><br>
        <input name="VAT" id="VAT" type="text" placeholder="1234567890" inputmode="numeric" pattern="[0-9]{9,}$" maxlength="10" required="required" oninvalid="setCustomValidity('Invalid VAT')" oninput="setCustomValidity('')"><br>

        <br><label for="name">Name</label><br>
        <input name="name" id="name" type="text" placeholder="João Silva" pattern="[A-z ]+$" maxlength="64" required="required" oninvalid="setCustomValidity('Invalid name')" oninput="setCustomValidity('')"><br>

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
        <input name="phone" id="phone" type="text" placeholder="(+351)912345678" inputmode="numeric" pattern="[0-9+]{9,}$" maxlength="13" required="required" oninvalid="setCustomValidity('Invalid Phone Number')" oninput="setCustomValidity('')"><br>

        <input type="submit" value="New Client">
    </form>

    <?php
    $value_VAT = isset($_POST['VAT']) ? $conn->real_escape_string(htmlspecialchars($_POST['VAT'])) : '';
    $value_name = isset($_POST['name']) ? $conn->real_escape_string(htmlspecialchars($_POST['name'])) : '';
    $value_birth_date = isset($_POST['birth_date']) ? $conn->real_escape_string(htmlspecialchars($_POST['birth_date'])) : '';
    $value_street = isset($_POST['street']) ? $conn->real_escape_string(htmlspecialchars($_POST['street'])) : '';
    $value_city = isset($_POST['city']) ? $conn->real_escape_string(htmlspecialchars($_POST['city'])) : '';
    $value_zip = isset($_POST['zip']) ? $conn->real_escape_string(htmlspecialchars($_POST['zip'])) : '';
    $value_gender = $_POST['gender'];
    $value_phone = isset($_POST['phone']) ? $conn->real_escape_string(htmlspecialchars($_POST['phone'])) : '';

    echo ($value_VAT);
    echo ($value_name);
    echo ($value_birth_date);
    echo ($value_street);
    echo ($value_city);
    echo ($value_zip);
    echo ($value_gender . "<br>");


    if (!empty($value_VAT) && !empty($value_name) && !empty($value_birth_date) && !empty($value_street) && !empty($value_city) && !empty($value_zip) && !empty($value_gender)) {


        $sql_client = "INSERT INTO client (VAT, name, birth_date, street, city, zip, gender, age)
        VALUES ('" . $value_VAT . "', '" . $value_name . "', '" . $value_birth_date . "', '" . $value_street . "', '" . $value_city . "', '" . $value_zip . "', '" . $value_gender . "', TIMESTAMPDIFF(YEAR,'" . $value_birth_date . "', CURDATE()))";

        $sql_phone = "INSERT INTO phone_number_client (VAT, phone)
        VALUES ('" . $value_VAT . "', '" . $value_phone . "')";

        if ($conn->query($sql_client) === TRUE) {
            echo "New client created successfully";
            if ($conn->query($sql_phone) === TRUE) {
                echo "New phone created successfully";
                header('Location: http://$_SERVER[HTTP_HOST]/client.php' . '?VAT=' . $value_VAT);
            } else {
                echo "Error: " . $sql_phone . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql_client . "<br>" . $conn->error;
        }
    }

    $conn->close();
    ?>

</body>

</html>