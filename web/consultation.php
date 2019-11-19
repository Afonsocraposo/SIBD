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
    $value_timestamp = $_GET['timestamp'];

    if (!empty($value_VAT) && !empty($value_timestamp)) {
        $stmt = $mysqli->stmt_init();
        $query_consultation = "SELECT * FROM appointment LEFT JOIN consultation ON (appointment.date_timestamp = consultation.date_timestamp AND appointment.VAT_doctor = consultation.VAT_doctor) WHERE appointment.VAT_doctor=? AND appointment.date_timestamp=?";

        $stmt->prepare($query_consultation);
        $stmt->bind_param('ss', $value_VAT, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the client data");
        } else {
            $result_consultation = $stmt->get_result();
        }

        if ($result_consultation && $result_consultation->num_rows > 0) {
            $consultation = $result_consultation->fetch_array();
            $SOAP_S = $consultation["SOAP_S"];
            $SOAP_O = $consultation["SOAP_O"];
            $SOAP_A = $consultation["SOAP_A"];
            $SOAP_P = $consultation["SOAP_P"];
        } else {
            echo "<script>location.href='" . $db->url() . "clients.php'</script>";
        }

        echo "Subjective:<br>";
        echo $SOAP_S;
        echo "<br><br>";
        echo "Objective:<br>";
        echo $SOAP_O;
        echo "<br><br>";
        echo "Assessment:<br>";
        echo $SOAP_A;
        echo "<br><br>";
        echo "Plan:<br>";
        echo $SOAP_P;
        echo "<br><br>";
    } else {
        echo "<script>location.href='" . $db->url() . "clients.php'</script>";
    }

    $mysqli->close();
    ?>

</body>

</html>