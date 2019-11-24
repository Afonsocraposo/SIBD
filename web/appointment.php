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
</head>

<body>
    <?php

    echo "<div style='width: 100%; text-align:center'><button name='' value='' style='font-size:2em;' onclick=\"location.href='" . $db->url() . "clients.php'\">&#127968;</button><br></div><br>";


    $value_date = isset($_GET['date']) ? $_GET['date'] : "";
    $value_client_VAT = isset($_GET['client']) ? $_GET['client'] : "";

    $value_rm_VAT = isset($_POST['VAT']) ? $_POST['VAT'] : "";
    $value_rm_dt = isset($_POST['dt']) ? $_POST['dt'] : "";

    $value_add_doctor = isset($_POST['add_doctor']) ? $_POST['add_doctor'] : "";
    $value_add_description = isset($_POST['add_description']) ? $_POST['add_description'] : "";
    $value_add_client = isset($_POST['add_client']) ? $_POST['add_client'] : "";
    $value_add_timestamp = isset($_POST['add_timestamp']) ? $_POST['add_timestamp'] : "";

    if (!empty($value_add_doctor) && !empty($value_add_description) && !empty($value_add_client) && !empty($value_add_timestamp)) {
        $query_add = "INSERT INTO appointment (VAT_doctor, VAT_client, date_timestamp, description)
    VALUES (?, ?, ?, ?)";
        $stmt = $dbh->prepare($query_add);
        $stmt->bindParam(1, $value_add_doctor);
        $stmt->bindParam(2, $value_add_client);
        $stmt->bindParam(3, $value_add_timestamp);
        $stmt->bindParam(4, $value_add_description);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching available doctors");
        }
    }

    $query_clients = "SELECT name, VAT FROM client";
    $stmt = $dbh->prepare($query_clients);
    if (!$stmt->execute()) {
        print("Something went wrong when fetching available doctors");
    } else {
        if ($stmt->rowCount() > 0) {
            $result_clients = $stmt->fetchAll();
            echo "<datalist id='clients'>";
            foreach ($result_clients as &$client) {
                echo "<option value=\"" . $client['VAT'] . "\">" . $client['name'] . "</option>";
            }
            echo "</datalist>";
        }
    }

    $query_available_doctors = "SELECT employee.name as name, doctor.VAT as VAT
    FROM employee
    INNER JOIN doctor
    ON employee.VAT = doctor.VAT
    WHERE doctor.VAT NOT IN
    (SELECT appointment.VAT_doctor
    FROM appointment 
    INNER JOIN employee 
    ON appointment.VAT_doctor = employee.VAT
    WHERE appointment.date_timestamp LIKE CONCAT(?,' ',?,':%'))";
    $stmt = $dbh->prepare($query_available_doctors);
    for ($i = 9; $i < 17; $i++) {
        $h = sprintf("%'.02d", $i);

        $stmt->bindParam(1, $value_date);
        $stmt->bindParam(2, $h);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching available doctors");
        } else {
            if ($stmt->rowCount() > 0) {
                $result_doctors = $stmt->fetchAll();
                echo "<datalist id='doctors$i'>";
                foreach ($result_doctors as &$doctor) {
                    echo "<option value=\"" . $doctor['VAT'] . "\">" . $doctor['name'] . "</option>";
                }
                echo "</datalist>";
            }
        }
    }

    if (!empty($value_date)) {

        if (!empty($value_rm_VAT) && !empty($value_rm_dt)) {
            $query_delete = "DELETE FROM appointment WHERE VAT_doctor = ? AND date_timestamp LIKE CONCAT(?,'%')";
            $stmt = $dbh->prepare($query_delete);
            $stmt->bindParam(1, $value_rm_VAT);
            $stmt->bindParam(2, $value_rm_dt);
            if (!$stmt->execute()) {
                print("Something went wrong when deleting the appointment");
            }
        }

        $query_count = "SELECT client.name as Cname, employee.name as Dname, HOUR(appointment.date_timestamp) as dt, appointment.description as description, appointment.VAT_doctor as DVAT, appointment.date_timestamp as date_timestamp
        FROM appointment 
        INNER JOIN employee 
        ON appointment.VAT_doctor = employee.VAT
        INNER JOIN client
        ON appointment.VAT_client = client.VAT
        WHERE appointment.date_timestamp LIKE CONCAT(?,'%')
        ORDER BY appointment.date_timestamp, employee.name ASC";

        $stmt = $dbh->prepare($query_count);
        $stmt->bindParam(1, $value_date);

        $result_appointments;
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the appointments");
        } else {
            if ($stmt->rowCount() > 0) {
                $result_appointments = $stmt->fetchAll();
            }
        }

        $hour = 9;
        if (!empty($result_appointments)) {
            echo ("<table>\n");
            echo ("<tr class='header'><td>Hour</td><td>Doctor</td><td>Client</td><td>Description</td><td>&#128465;</td></tr>\n");
            foreach ($result_appointments as &$appointment) {
                while ($appointment['dt'] >= $hour) {
                    $date = $value_date . " " . sprintf("%'.02d:00", $hour);
                    echo "<tr class='hour' id='$hour' onclick='prompt(\"$date\", \"$value_client_VAT\")'><td>" . sprintf("%'.02d:00", $hour) . "</td></tr>";
                    $hour++;
                }

                echo "<tr onclick=\"location.href='" . $db->url() . "consultation.php?VAT=" . $appointment["DVAT"] . "&timestamp=" . $appointment["date_timestamp"] . "'\"><td></td><td>" . $appointment['Dname'] . "</td><td>" . $appointment['Cname'] . "</td><td>" . $appointment['description'] . "</td>
                <td>
                    <form action='' method='post'>
                        <input style='display:none' name='VAT' value='" . $appointment['DVAT'] . "'>
                        <input style='display:none' name='dt' value='" . $appointment['date_timestamp'] . "'>
                        <button name='rm_appointment' value='' style='background:red; color:white'>&#10008;</button>
                    </form>
                </td>
                </tr>\n";
            }
            while (17 > $hour) {
                $date = $value_date . " " . sprintf("%'.02d:00", $hour);
                echo "<tr class='hour' id='$hour' onclick='prompt(\"$date\", \"$value_client_VAT\")'><td>" . sprintf("%'.02d:00", $hour) . "</td></tr>";
                $hour++;
            }
            echo ("</table>\n");
        } else {
            echo ("<table>\n");
            echo ("<tr class='header'><td>Hour</td><td>Doctor</td><td>Client</td><td>Description</td><td>&#128465;</td></tr>\n");
            while (17 > $hour) {
                $date = $value_date . " " . sprintf("%'.02d:00", $hour);
                echo "<tr class='hour' id='$hour' onclick='prompt(\"$date\", \"$value_client_VAT\")'><td>" . sprintf("%'.02d:00", $hour) . "</td></tr>";
                $hour++;
            }
            echo ("</table>\n");
        }
    } else {
        echo "<script>location.href='" . $db->url() . "appointments.php'</script>";
    }


    $dbh = null;
    ?>

    <script>
        function prompt(date, client) {
            console.log(client);
            document.getElementById("popup").style.display = "block";
            document.getElementById("date").innerHTML = date;
            document.getElementById("add_timestamp").value = date;
            document.getElementById("doctor").innerHTML = "<input list='doctors" + parseInt(date.slice(11, 13)) + "' name='add_doctor' id='add_doctor' required style='width:200px'>";
            if (client.length >= 9) {
                document.getElementById("client").style.display = 'none';
                document.getElementById("add_client").value = client;
            }
        }
    </script>

    <div id="popup">
        <div style="float:right"><button onclick="document.getElementById('popup').style.display = 'none';">X</button></div><br><br>
        <div>
            <form action='' method='post'>
                <h2 id="date"></h2>
                <input name="add_timestamp" id="add_timestamp" style="display:none">
                <span id="client">
                    <label for="add_client">Client: </label>
                    <input list='clients' name='add_client' id='add_client' style="width:200px" required>
                </span>
                <label for="add_doctor">Doctor: </label>
                <span id="doctor"></span>
                <br>
                <br>
                <label for="add_description">Description: </label>
                <textarea rows='4' wrap='hard' name='add_description' id="add_description" required></textarea><br>
                <br>
                <div style="width: 100%; text-align: center">
                    <button name='' value='test'>SUBMIT</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>