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
    <?php
    $value_VAT = $_GET['VAT'];

    if (!empty($value_VAT)) {
        $stmt = $mysqli->stmt_init();
        $query_client = "SELECT * FROM client WHERE VAT=?";
        $query_phone = "SELECT * FROM phone_number_client WHERE VAT=?";
        $query_appointments = "SELECT appointment.VAT_doctor, appointment.date_timestamp, appointment.description, consultation.date_timestamp as consultation FROM appointment LEFT JOIN consultation ON (appointment.date_timestamp = consultation.date_timestamp AND appointment.VAT_doctor = consultation.VAT_doctor) WHERE appointment.VAT_client=?";

        $stmt->prepare($query_client);
        $stmt->bind_param('s', $value_VAT);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the client data");
        } else {
            $result_client = $stmt->get_result();
        }

        $stmt->prepare($query_phone);
        $stmt->bind_param('s', $value_VAT);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the client phone");
        } else {
            $result_phone = $stmt->get_result();
        }

        $stmt->prepare($query_appointments);
        $stmt->bind_param('s', $value_VAT);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the client appointments");
        } else {
            $result_appointments = $stmt->get_result();
        }

        if ($result_client && $result_client->num_rows > 0) {
            $client = $result_client->fetch_array();
            $VAT = $client['VAT'];
            $name = $client['name'];
            $birth_date = $client['birth_date'];
            $street = $client['street'];
            $zip = $client['zip'];
            $city = $client['city'];
            $gender = $client['gender'];
            $age = $client['age'];
        } else {
            die();
        }

        if ($result_phone && $result_phone->num_rows > 0) {
            $phone = $result_phone->fetch_array();
            $phone_number = $phone['phone'];
        } else {
            die();
        }

        echo "VAT: " . $VAT . "<br>";
        echo "Name: " . $name . "<br>";
        echo "Date of Birth: " . $birth_date . "<br>";
        echo "Address: " . $street . ", " . $zip . " " . $city . "<br>";
        echo "Gender: " . $gender . "<br>";
        echo "Age: " . $age . "<br>";
        echo "Phone Number: " . $phone_number . "<br>";

        echo "<br>Appointments:<br><br>";
        if ($result_appointments && $result_appointments->num_rows > 0) {
            echo ("<table border=\"1\">\n");
            echo ("<tr><td>Doctor's VAT</td><td>Date Timestamp</td><td>Description</td><td>Attended</td></tr>\n");
            while ($appointment = $result_appointments->fetch_array()) {
                if ($appointment['consultation'] == null) {
                    echo "<tr><td>" . $appointment['VAT_doctor'] . "</td><td>" . $appointment['date_timestamp'] . "</td><td>" . $appointment['description'] . "</td><td style=\"color:red\">&#10008;</td></tr>\n";
                } else {
                    echo "<tr onclick=\" location.href = '" . $url . "consultation.php?VAT=" . $appointment['VAT_doctor'] . "&timestamp=" . $appointment['date_timestamp'] . "';\"><td>" . $appointment['VAT_doctor'] . "</td><td>" . $appointment['date_timestamp'] . "</td><td>" . $appointment['description'] . "</td><td style=\"color:green\">&#10004;</td></tr>\n";
                }
            }
            echo ("</table>\n");
        } else {
            echo "No results";
        }
    } else {
        echo "<script>location.href='" . $db->url() . "clients.php'</script>";
    }

    $mysqli->close();
    ?>

</body>

</html>