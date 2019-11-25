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
    <style>
        .container {
            widht: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        img {
            width: 15vw;
            height: 15vw;
        }

        .one {
            flex: 0 0 20vw;
            text-align: center;
        }

        .two {
            flex: 1;
        }
    </style>
</head>

<body>
    <?php

    $value_VAT = isset($_GET['VAT']) ? $_GET['VAT'] : "";
    $client = null;
    $result_appointments = null;

    if (!empty($value_VAT)) {
        $query_client = "SELECT * FROM client INNER JOIN phone_number_client ON client.VAT = phone_number_client.VAT WHERE client.VAT=?";
        $query_appointments = "SELECT appointment.VAT_doctor, employee.name, appointment.date_timestamp, appointment.description, consultation.date_timestamp as consultation
        FROM appointment
        INNER JOIN employee
        ON appointment.VAT_doctor=employee.VAT
        LEFT JOIN consultation
        ON (appointment.date_timestamp = consultation.date_timestamp AND appointment.VAT_doctor = consultation.VAT_doctor)
        WHERE appointment.VAT_client=?
        ORDER BY appointment.date_timestamp DESC";

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


        echo "<div style='width: 100%; text-align:center'><button name='' value='' style='font-size:2em;' onclick=\"location.href='" . $db->url() . "clients.php'\">&#127968;</button><br>
        <br><form action='appointments.php' method='post'>
                <input style='display:none' name='VAT' value='$VAT'>
                <input type='submit' value='New Appointment'>
                </form>
        </div><br>";

        echo "<div class='container'><div class='one'>
        <img id='profileImage' src='http://web.tecnico.ulisboa.pt/ist425108/SIBD/images/profile/$gender.png'/>";


        echo "</div><div class='two'>";
        echo "<h1>$name</h1>";
        echo "<h4>Address:</h4> $street, $zip $city<br>";
        echo "<h4>Age:</h4> $age<br>";
        echo "<h4>Gender:</h4> $gender<br>";
        echo "<h4>Date of Birth:</h4> $birth_date<br>";
        echo "<h4>Phone Number:</h4> $phone_number<br>";
        echo "<h4>VAT:</h4> $VAT<br>";

        echo "</div></div>
        <div style='width:100%'>";

        echo "<h2>Appointments:</h2>";
        if (!empty($result_appointments)) {

            date_default_timezone_set('Europe/London');
            $current_date = time();

            echo ("<table>\n");
            echo ("<tr class='header'><td>Date Timestamp</td><td>Doctor</td><td>Description</td><td>Attended</td></tr>\n");
            foreach ($result_appointments as &$appointment) {
                if ($appointment['consultation'] == null) {
                    echo "<tr><td>" . $appointment['date_timestamp'] . "</td><td>" . $appointment['name'] . "</td><td>" . $appointment['description'] . "</td>
                    <td>";
                    if ($current_date >= strtotime($appointment['date_timestamp']) + 60 * 60 * 24) {
                        echo "<span style='color:red'>&#10008;</span>";
                    } elseif ($current_date >= strtotime($appointment['date_timestamp'])) {
                        echo "<form action='consultation.php' method='post'>
                        <input style='display:none' name='new_VAT' value='" . $appointment['VAT_doctor'] . "'>
                        <input style='display:none' name='new_timestamp' value='" . $appointment['date_timestamp'] . "'>
                        <button name='' value='' style='background:red; color:white; font-weight:bold'>?</button>
                    </form>";
                    } else {
                        echo "<span style='color:red'><b>?<b/></span>";
                    }
                    echo "</td>
                    </tr>\n";
                } else {
                    echo "<tr onclick=\"location.href = '" . $db->url() . "consultation.php?VAT=" . $appointment['VAT_doctor'] . "&timestamp=" . $appointment['date_timestamp'] . "';\"><td>" . $appointment['date_timestamp'] . "</td><td>" . $appointment['name'] . "</td><td>" . $appointment['description'] . "</td><td style=\"color:green\">&#10004;</td></tr>\n";
                }
            }
            echo ("</table>\n");
        } else {
            echo "No results";
        }
    } else {
        echo "<script>location.href='" . $db->url() . "clients.php'</script>";
    }

    echo "</div>";

    $dbh = null;
    ?>

</body>

</html>