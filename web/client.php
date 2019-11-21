<?php
include("database.php");
$db = new Database();
$dbh = $db->connect();

?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>SIBD</title>
</head>

<body>
    <?php
    $value_VAT = $_GET['VAT'];
    $client;
    $result_appointments;

    if (!empty($value_VAT)) {
        $query_client = "SELECT * FROM client INNER JOIN phone_number_client ON client.VAT = phone_number_client.VAT WHERE client.VAT=?";
        $query_appointments = "SELECT appointment.VAT_doctor, appointment.date_timestamp, appointment.description, consultation.date_timestamp as consultation FROM appointment LEFT JOIN consultation ON (appointment.date_timestamp = consultation.date_timestamp AND appointment.VAT_doctor = consultation.VAT_doctor) WHERE appointment.VAT_client=?";

        $stmt = $dbh->prepare($query_client);
        $stmt->bindParam(1, $value_VAT);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the client data");
        } else {
            if ($stmt->rowCount() > 0) {
                $client = $stmt->fetch();
            }
        }

        $stmt = $dbh->prepare($query_appointments);
        $stmt->bindParam(1, $value_VAT);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the client appointments");
        } else {
            if ($stmt->rowCount() > 0) {
                $result_appointments = $stmt->fetchAll();
            }
        }
        $stmt = null;

        if ($client != null) {
            $VAT = $client['VAT'];
            $name = $client['name'];
            $birth_date = $client['birth_date'];
            $street = $client['street'];
            $zip = $client['zip'];
            $city = $client['city'];
            $gender = $client['gender'];
            $age = $client['age'];
            $phone_number = $client['phone'];
        } else {
            echo "<script>location.href='" . $db->url() . "clients.php'</script>";
            die();
        }

        echo "VAT: $VAT<br>";
        echo "Name: $name<br>";
        echo "Date of Birth: $birth_date<br>";
        echo "Address: $street, $zip $city<br>";
        echo "Gender: $gender<br>";
        echo "Age: $age<br>";
        echo "Phone Number: $phone_number<br>";

        echo "<br>Appointments:<br><br>";
        if ($result_appointments != null) {
            echo ("<table border=\"1\">\n");
            echo ("<tr><td>Doctor's VAT</td><td>Date Timestamp</td><td>Description</td><td>Attended</td></tr>\n");
            foreach ($result_appointments as &$appointment) {
                if ($appointment['consultation'] == null) {
                    echo "<tr><td>" . $appointment['VAT_doctor'] . "</td><td>" . $appointment['date_timestamp'] . "</td><td>" . $appointment['description'] . "</td><td style=\"color:red\">&#10008;</td></tr>\n";
                } else {
                    echo "<tr onclick=\"location.href = '" . $url . "consultation.php?VAT=" . $appointment['VAT_doctor'] . "&timestamp=" . $appointment['date_timestamp'] . "';\"><td>" . $appointment['VAT_doctor'] . "</td><td>" . $appointment['date_timestamp'] . "</td><td>" . $appointment['description'] . "</td><td style=\"color:green\">&#10004;</td></tr>\n";
                }
            }
            echo ("</table>\n");
        } else {
            echo "No results";
        }
    } else {
        echo "<script>location.href='" . $db->url() . "clients.php'</script>";
    }

    $dbh = null;
    ?>

</body>

</html>