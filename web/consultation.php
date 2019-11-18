<?php
include("database.php");
$db = new Database();
$conn = $db->connect();
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>SIBD</title>
</head>

<body>
    <?php
    $value_VAT = isset($_GET['VAT']) ? $conn->real_escape_string(htmlspecialchars($_GET['VAT'])) : '';

    if (!empty($value_VAT)) {
        $query_client = "SELECT * FROM client WHERE VAT=" . $value_VAT;
        $query_phone = "SELECT * FROM phone_number_client WHERE VAT=" . $value_VAT;
        $query_appointments_consultations = "SELECT appointment.VAT_doctor, appointment.date_timestamp, appointment.description, consultation.date_timestamp as consultation FROM appointment LEFT JOIN consultation ON (appointment.date_timestamp = consultation.date_timestamp AND appointment.VAT_doctor = consultation.VAT_doctor) WHERE appointment.VAT_client=" . $value_VAT;

        $raw_results_client = $conn->query($query_client) or die(mysqli_query_error());
        $raw_results_phone = $conn->query($query_phone) or die(mysqli_query_error());

        if ($raw_results_client && $raw_results_client->num_rows > 0) {
            $results_client = $raw_results_client->fetch_array();
            $VAT = $results_client['VAT'];
            $name = $results_client['name'];
            $birth_date = $results_client['birth_date'];
            $street = $results_client['street'];
            $zip = $results_client['zip'];
            $city = $results_client['city'];
            $gender = $results_client['gender'];
            $age = $results_client['age'];
        }

        if ($raw_results_phone && $raw_results_phone->num_rows > 0) {
            $results_phone = $raw_results_phone->fetch_array();
            $phone = $results_phone['phone'];
        }
        echo "VAT: " . $VAT . "<br>";
        echo "Name: " . $name . "<br>";
        echo "Date of Birth: " . $birth_date . "<br>";
        echo "Address: " . $street . ", " . $zip . " " . $city . "<br>";
        echo "Gender: " . $gender . "<br>";
        echo "Age: " . $age . "<br>";
        echo "Phone Number: " . $phone . "<br>";

        echo "<br>Appointments:<br><br>";
        $raw_results = $conn->query($query_appointments_consultations) or die(mysqli_query_error());
        if ($raw_results && $raw_results->num_rows > 0) {
            echo ("<table border=\"1\">\n");
            echo ("<tr><td>Doctor's VAT</td><td>Date Timestamp</td><td>Description</td><td>Consultation</td></tr>\n");
            while ($results = $raw_results->fetch_array()) {
                echo "<tr><td>" . $results['VAT_doctor'] . "</td><td>" . $results['date_timestamp'] . "</td><td>" . $results['description'] . "</td><td onclick=\" location.href = '" . $url . "client.php?VAT=" . $value_VAT . "';\">" . ($results['consultation'] == null ? "Missed" : "Details") . "</td></tr>\n";
            }
            echo ("</table>\n");
        } else {
            echo "No results";
        }
    }

    $conn->close();
    ?>

</body>

</html>