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

    $value_add_diagnostic = $_POST['add_diagnostic'];
    $value_rm_diagnostic = $_POST['rm_diagnostic'];

    $value_add_medication = $_POST['add_medication'];
    $pieces = explode(", ", $value_add_medication);
    $value_add_medication_name = $pieces[0];
    $value_add_medication_lab = $pieces[1];
    $value_add_prescription_ID = $_POST['add_medication_ID'];
    $value_add_dosage = $_POST['add_dosage'];
    $value_add_regime = $_POST['add_regime'];

    $value_save_SOAP_S = $_POST['save_SOAP_S'];
    $value_save_SOAP_O = $_POST['save_SOAP_O'];
    $value_save_SOAP_A = $_POST['save_SOAP_A'];
    $value_save_SOAP_P = $_POST['save_SOAP_P'];



    if (!empty($value_VAT) && !empty($value_timestamp)) {
        $stmt = $mysqli->stmt_init();

        $query_save_SOAP_S = "UPDATE consultation
        SET SOAP_S=?
        WHERE VAT_doctor=? AND date_timestamp=?";

        $query_save_SOAP_O = "UPDATE consultation
        SET SOAP_O=?
        WHERE VAT_doctor=? AND date_timestamp=?";

        $query_save_SOAP_A = "UPDATE consultation
        SET SOAP_A=?
        WHERE VAT_doctor=? AND date_timestamp=?";

        $query_save_SOAP_P = "UPDATE consultation
        SET SOAP_P=?
        WHERE VAT_doctor=? AND date_timestamp=?";

        $query_consultation = "SELECT client.name, client.gender, client.age, employee.name as drname, doctor.specialization, appointment.description, consultation.* 
        FROM client 
        INNER JOIN appointment 
        ON client.VAT = appointment.VAT_client 
        INNER JOIN employee 
        ON appointment.VAT_doctor = employee.VAT 
        INNER JOIN doctor 
        ON employee.VAT = doctor.VAT 
        LEFT JOIN consultation 
        ON (appointment.date_timestamp = consultation.date_timestamp AND appointment.VAT_doctor = consultation.VAT_doctor)
        WHERE consultation.VAT_doctor=? AND consultation.date_timestamp=?";

        $query_diagnostic = "SELECT *
        FROM consultation_diagnostic
        INNER JOIN diagnostic_code
        ON consultation_diagnostic.ID = diagnostic_code.ID
        WHERE consultation_diagnostic.VAT_doctor = ?
        AND consultation_diagnostic.date_timestamp = ?";

        $query_rm_diagnostic = "DELETE FROM consultation_diagnostic
        WHERE VAT_doctor = ?
        AND date_timestamp = ?
        AND ID = ?";

        $query_add_diagnostic = "INSERT INTO consultation_diagnostic (VAT_doctor, date_timestamp, ID)
        VALUES (?, ? , ?)";

        $query_available_diagnostic = "SELECT * 
        FROM diagnostic_code
        WHERE ID NOT IN (
        SELECT consultation_diagnostic.ID
        FROM consultation_diagnostic
        WHERE consultation_diagnostic.VAT_doctor = ?
        AND consultation_diagnostic.date_timestamp = ?)";

        $query_add_prescription = "INSERT INTO prescription (name, lab, VAT_doctor, date_timestamp, ID, dosage, description)
        VALUES (?, ? , ?, ?, ?, ?, ?)";

        $query_available_prescription = "SELECT * 
        FROM medication";

        $query_nurse = "SELECT *
        FROM consultation_assistant
        INNER JOIN employee
        ON consultation_assistant.VAT_nurse = employee.VAT
        WHERE consultation_assistant.VAT_doctor = ?
        AND consultation_assistant.date_timestamp = ?";

        $query_prescription = "SELECT *
        FROM prescription
        WHERE VAT_doctor = ?
        AND date_timestamp = ?";

        $query_procedure = "SELECT *
        FROM procedure_
        INNER JOIN procedure_in_consultation
        ON procedure_.name = procedure_in_consultation.name
        WHERE procedure_in_consultation.VAT_doctor = ?
        AND procedure_in_consultation.date_timestamp = ?";

        if (!empty($value_save_SOAP_S)) {
            $stmt->prepare($query_save_SOAP_S);
            $stmt->bind_param('sss', $value_save_SOAP_S, $value_VAT, $value_timestamp);
            if (!$stmt->execute()) {
                print("Something went wrong when saving the SOAP\n");
            }
        }

        if (!empty($value_save_SOAP_O)) {
            $stmt->prepare($query_save_SOAP_O);
            $stmt->bind_param('sss', $value_save_SOAP_O, $value_VAT, $value_timestamp);
            if (!$stmt->execute()) {
                print("Something went wrong when saving the SOAP\n");
            }
        }

        if (!empty($value_save_SOAP_A)) {
            $stmt->prepare($query_save_SOAP_A);
            $stmt->bind_param('sss', $value_save_SOAP_A, $value_VAT, $value_timestamp);
            if (!$stmt->execute()) {
                print("Something went wrong when saving the SOAP\n");
            }
        }

        if (!empty($value_save_SOAP_P)) {
            $stmt->prepare($query_save_SOAP_P);
            $stmt->bind_param('sss', $value_save_SOAP_P, $value_VAT, $value_timestamp);
            if (!$stmt->execute()) {
                print("Something went wrong when saving the SOAP\n");
            }
        }

        $stmt->prepare($query_consultation);
        $stmt->bind_param('ss', $value_VAT, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the client data");
        } else {
            $result_consultation = $stmt->get_result();
        }

        $stmt->prepare($query_nurse);
        $stmt->bind_param('ss', $value_VAT, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the nurses involved");
        } else {
            $result_nurse = $stmt->get_result();
        }

        if (!empty($value_rm_diagnostic)) {
            $stmt->prepare($query_rm_diagnostic);
            $stmt->bind_param('sss', $value_VAT, $value_timestamp, $value_rm_diagnostic);
            if (!$stmt->execute()) {
                //print($mysqli->error);
            }
        }

        if (!empty($value_add_diagnostic)) {
            $stmt->prepare($query_add_diagnostic);
            $stmt->bind_param('sss', $value_VAT, $value_timestamp, $value_add_diagnostic);
            if (!$stmt->execute()) {
                //print($mysqli->error);
            }
        }

        $stmt->prepare($query_diagnostic);
        $stmt->bind_param('ss', $value_VAT, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the diagnostic codes");
        } else {
            $result_diagnostic = $stmt->get_result();
        }

        $stmt->prepare($query_available_diagnostic);
        $stmt->bind_param('ss', $value_VAT, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the available diagnostic codes");
        } else {
            $result_available_diagnostic = $stmt->get_result();
        }

        if (!empty($value_add_medication_name) && !empty($value_add_medication_lab) && !empty($value_add_dosage) && !empty($value_add_regime)) {
            $stmt->prepare($query_add_prescription);
            $stmt->bind_param('sssssss', $value_add_medication_name, $value_add_medication_lab, $value_VAT, $value_timestamp, $value_add_prescription_ID, $value_add_dosage, $value_add_regime);
            if (!$stmt->execute()) {
                //print($mysqli->error);
            }
        }

        $stmt->prepare($query_prescription);
        $stmt->bind_param('ss', $value_VAT, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the prescriptions");
        } else {
            $result_prescription = $stmt->get_result();
        }

        $stmt->prepare($query_available_prescription);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the available medication");
        } else {
            $result_available_prescription = $stmt->get_result();
        }

        echo "<datalist id='medication'>";
        while ($medication = $result_available_prescription->fetch_array()) {
            echo "<option value=\"" . $medication['name'] . ", " . $medication['lab'] . "\"></option>";
        }
        echo "</datalist>";

        $stmt->prepare($query_procedure);
        $stmt->bind_param('ss', $value_VAT, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the prescriptions");
        } else {
            $result_procedure = $stmt->get_result();
        }

        if ($result_consultation && $result_consultation->num_rows > 0) {
            $consultation = $result_consultation->fetch_array();
            echo "<b>Client Details:</b><br>";
            echo "Name: " . $consultation["name"];
            echo "<br>";
            echo "Gender: " . $consultation["gender"];
            echo "<br>";
            echo "Age: " . $consultation["age"];
            echo "<br><br>";
            echo "<b>Doctor Details:</b><br>";
            echo "Name: " . $consultation["drname"];
            echo "<br>";
            echo "Specialization: " . $consultation["specialization"];
            echo "<br><br>";
            echo "<b>Appointment Details:</b><br>";
            echo "Date: " . $consultation["date_timestamp"];
            echo "<br>";
            echo "Description: " . $consultation["description"];
            echo "<br><br>";

            echo "<form action='' id='save_SOAP' method='post'>
            <b>Consultation Notes:</b> <input type='submit' value='&#128190;' method='post'>
            </form>";
            echo "Subjective:<br>";
            echo "<textarea rows='4' cols='100' maxlength='512' wrap='hard' name='save_SOAP_S' form='save_SOAP'>" . $consultation["SOAP_S"] . "</textarea><br>";
            echo "<br>";
            echo "Objective:<br>";
            echo "<textarea rows='4' cols='100' maxlength='512' wrap='hard' name='save_SOAP_O' form='save_SOAP'>" . $consultation["SOAP_O"] . "</textarea><br>";
            echo "<br>";
            echo "Assessment:<br>";
            echo "<textarea rows='4' cols='100' maxlength='512' wrap='hard' name='save_SOAP_A' form='save_SOAP'>" . $consultation["SOAP_A"] . "</textarea><br>";
            echo "<br>";
            echo "Plan:<br>";
            echo "<textarea rows='4' cols='100' maxlength='512' wrap='hard' name='save_SOAP_P' form='save_SOAP'>" . $consultation["SOAP_P"] . "</textarea><br>";
            echo "<br>";
        } else {
            echo "<script>location.href='" . $db->url() . "clients.php'</script>";
        }

        echo "<br>";
        echo "<b>Nurse(s) Assisting:</b><br>";
        if ($result_nurse && $result_nurse->num_rows > 0) {
            echo ("<table border=\"1\">\n");
            echo ("<tr><td>Name</td></tr>\n");
            while ($nurse = $result_nurse->fetch_array()) {
                echo "<tr onclick=\" location.href = '" . $url . "employee.php?VAT=" . $nurse['VAT'] . "';\"><td>" . $nurse['name'] . "</td></tr>\n";
            }
            echo ("</table>\n");
        } else {
            echo "No medication.";
        }

        echo "<br>";
        echo "<b>Diagnostic(s):</b><br>";
        if ($result_diagnostic && $result_diagnostic->num_rows > 0) {
            echo ("<table border=\"1\">\n");
            echo ("<tr><td>Diagnostic Code</td><td>Description</td><td>&#128465;</td><td>Medication</td></tr>\n");
            while ($diagnostic = $result_diagnostic->fetch_array()) {
                echo "<tr>
                <td>" . $diagnostic['ID'] . "</td>
                <td>" . $diagnostic['description'] . "</td>
                <td>
                    <form action='' method='post'>
                        <button name='rm_diagnostic' value='" . $diagnostic['ID'] . "'>&#10008;</button>
                    </form>
                </td>
                <td>";
                echo "<button id='" . $diagnostic['ID'] . "' onclick='promptPrescription(\"" . $diagnostic['ID'] . "\")'><span style='color:green'>&#10010;</span></button>
                </td>
                </tr>\n";
            }
            echo ("</table>\n");
        } else {
            echo "No diagnostic.";
        }

        if ($result_available_diagnostic && $result_available_diagnostic->num_rows > 0) {
            echo "<form action='' method='post'>
            <input type='submit' style='color:green' value='&#10010;'>
            <input list='diagnostics' name='add_diagnostic' required>
            <datalist id='diagnostics'>";
            while ($diagnostic_code = $result_available_diagnostic->fetch_array()) {
                echo "<option value=\"" . $diagnostic_code['ID'] . "\">" . $diagnostic_code['description'] . "</option>";
            }
            echo "</datalist>
            </form>";
        }

        echo "<br>";
        echo "<b>Prescription(s):</b><br>";
        if ($result_prescription && $result_prescription->num_rows > 0) {
            echo ("<table border=\"1\">\n");
            echo ("<tr><td>Name</td><td>Lab</td><td>Diagnostic</td><td>Dosage</td><td>Regime</td></tr>\n");
            while ($prescription = $result_prescription->fetch_array()) {
                echo "<tr onclick=\" location.href = '" . $url . "medication.php?name=" . $prescription['name'] . "&lab=" . $prescription['lab'] . "';\"><td>" . $prescription['name'] . "</td><td>" . $prescription['lab'] . "</td><td>" . $prescription['ID'] . "</td><td>" . $prescription['dosage'] . "</td><td>" . $prescription['description'] . "</td></tr>\n";
            }
            echo ("</table>\n");
        } else {
            echo "No medication.";
        }

        echo "<br><br>";
        echo "<b>Procedure(s):</b><br>";
        if ($result_procedure && $result_procedure->num_rows > 0) {
            echo ("<table border=\"1\">\n");
            echo ("<tr><td>Type</td><td>Name</td><td>Description</td></tr>\n");
            while ($procedure = $result_procedure->fetch_array()) {
                echo "<tr><td>" . $procedure['type'] . "</td><td>" . $procedure['name'] . "</td><td>" . $procedure['description'] . "</td></tr>\n";
            }
            echo ("</table>\n");
        } else {
            echo "No medication.";
        }
    } else {
        //echo "<script>location.href='" . $db->url() . "clients.php'</script>";
    }

    $mysqli->close();
    ?>

    <script>
        function promptPrescription(ID) {
            document.getElementById("popupPrescription").style.display = "block";
            var left = window.scrollX + document.getElementById(ID).getBoundingClientRect().left;
            var top = window.scrollY + document.getElementById(ID).getBoundingClientRect().top;
            document.getElementById("popupPrescription").style.left = left + 30;
            document.getElementById("popupPrescription").style.top = top;
            document.getElementById("add_medication_ID").value = ID;
        }
    </script>

    <div id="popupPrescription" style="width:240px; top:0; padding:15px; padding-bottom:0; display:none; position:absolute; background-color:white; border-style: solid; border-width: 1px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
        <div style="float:right"><button onclick="document.getElementById('popupPrescription').style.display = 'none';">X</button></div><br>
        <form action='' method='post'>
            <input type="text" name="add_medication_ID" id="add_medication_ID" style="display:none">
            <label for="add_medication">Medication: </label>
            <input list='medication' name='add_medication' id='add_medication' required style="width:125px"><br>
            <label for="dosage">Dosage: </label>
            <input name="add_dosage" id="dosage" type="text" placeholder="e.g. 1000 mg" maxlength="32" required oninvalid="setCustomValidity('Invalid Dosage')" oninput="setCustomValidity('')" style="width:150px"><br>
            <label for="regime">Regime: </label>
            <input name="add_regime" id="regime" type="text" placeholder="e.g. 1 pill every day" maxlength="64" required oninvalid="setCustomValidity('Invalid Regime')" oninput="setCustomValidity('')" style="width:148px"><br>
            <br>
            <input type='submit'>
        </form>
    </div>

</body>

</html>