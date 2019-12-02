<?php
include("database.php");
$db = new Database();
$dbh = $db->connect();

?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Consultation</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 0 0 50vw;
        }

        img {
            width: 10vw;
            height: 10vw;
        }

        .one {
            flex: 0 0 20vw;
            text-align: center;
        }

        .two {
            flex: 1;
        }

        body {
            text-align: center;
        }

        #popupPrescription {
            text-align: right;
            width: 310px;
            top: 0;
            padding: 15px;
            display: none;
            position: absolute;
            background-color: white;
            border-style: solid;
            border-width: 1px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
    </style>
</head>

<body>

    <?php


    $value_new_VAT = isset($_POST['new_VAT']) ? $_POST['new_VAT'] : "";
    $value_new_timestamp = isset($_POST['new_timestamp']) ? $_POST['new_timestamp'] : "";

    if (!empty($value_new_VAT) && !empty($value_new_timestamp)) {
        $query_new_consultation = "INSERT INTO consultation (VAT_doctor, date_timestamp)
        VALUES (?, ?)";
        $stmt = $dbh->prepare($query_new_consultation);
        $stmt->bindParam(1, $value_new_VAT);
        $stmt->bindParam(2, $value_new_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when creating consultation");
        }
        echo "<script>location.href='" . $db->url() . "consultation.php?VAT=$value_new_VAT&timestamp=$value_new_timestamp'</script>";
    }

    $value_VAT = isset($_GET['VAT']) ? $_GET['VAT'] : "";
    $value_timestamp = isset($_GET['timestamp']) ? $_GET['timestamp'] : "";

    if (!empty($value_VAT) && !empty($value_timestamp)) {

        $value_charting = isset($_POST['charting']) ? $_POST['charting'] : "";

        if (!empty($value_charting)) {
            $quadrants = ['UR', 'UL', 'LR', 'LL'];
            $quadrantsExt = ['Upper right', 'Upper left', 'Lower right', 'Lower left'];

            $query_chart_exists = "SELECT COUNT(*) as c FROM procedure_charting WHERE VAT=? AND date_timestamp=? AND quadrant=? AND number=?";
            $query_tooth_insert = "INSERT INTO procedure_charting (name, VAT, date_timestamp, quadrant, number, description, measure)
                                    VALUES ('Periodontal charting', ?, ?, ?, ?, ?, ?)";
            $query_tooth_update = "UPDATE procedure_charting
                                    SET description=?, measure=?
                                    WHERE name LIKE 'Periodontal charting'
                                    AND VAT=?
                                    AND date_timestamp=?
                                    AND quadrant=?
                                    AND number=?";

            try {
                $dbh->beginTransaction();
                for ($q = 0; $q < 4; $q++) {
                    $quadrant = $quadrants[$q];
                    $quadrantExt = $quadrantsExt[$q];
                    for ($number = 1; $number <= 8; $number++) {
                        $value_tooth = isset($_POST[$quadrant . $number]) ? $_POST[$quadrant . $number] : "";
                        $value_tooth_desc = isset($_POST[$quadrant . $number . "desc"]) ? $_POST[$quadrant . $number . "desc"] : "";
                        if (!empty($value_tooth) || !empty($value_tooth_desc)) {

                            $stmt = $dbh->prepare($query_chart_exists);
                            $stmt->bindParam(1, $value_VAT);
                            $stmt->bindParam(2, $value_timestamp);
                            $stmt->bindParam(3, $quadrantExt);
                            $stmt->bindParam(4, $number);
                            if (!$stmt->execute()) {
                                print("Something went wrong when fetching tooth name");
                            } else {
                                $result_exists = $stmt->fetch();
                                if ($result_exists != null) {
                                    if ($result_exists["c"] != 0) {
                                        $stmt = $dbh->prepare($query_tooth_update);
                                        $stmt->bindParam(1, $value_tooth_desc);
                                        $stmt->bindParam(2, $value_tooth);
                                        $stmt->bindParam(3, $value_VAT);
                                        $stmt->bindParam(4, $value_timestamp);
                                        $stmt->bindParam(5, $quadrantExt);
                                        $stmt->bindParam(6, $number);
                                        if (!$stmt->execute()) {
                                            print("Something went wrong when updating tooth charting<br>");
                                        }
                                    } else {
                                        $stmt = $dbh->prepare($query_tooth_insert);
                                        $stmt->bindParam(1, $value_VAT);
                                        $stmt->bindParam(2, $value_timestamp);
                                        $stmt->bindParam(3, $quadrantExt);
                                        $stmt->bindParam(4, $number);
                                        $stmt->bindParam(5, $value_tooth_desc);
                                        $stmt->bindParam(6, $value_tooth);
                                        if (!$stmt->execute()) {
                                            print("Something went wrong when inserting tooth charting<br>");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $dbh->commit();
            } catch (Exception $e) {
                echo "Error:  " . $e;
            }
        }

        $value_add_diagnostic = isset($_POST['add_diagnostic']) ? $_POST['add_diagnostic'] : "";
        $value_rm_diagnostic = isset($_POST['rm_diagnostic']) ? $_POST['rm_diagnostic'] : "";

        $value_add_medication = isset($_POST['add_medication']) ? $_POST['add_medication'] : "";
        if (empty($value_add_medication)) {
            $value_add_medication_name = "";
            $value_add_medication_lab = "";
        } else {
            $pieces = explode(", ", $value_add_medication);
            $value_add_medication_name = $pieces[0];
            $value_add_medication_lab = $pieces[1];
        }

        $value_add_prescription_ID = isset($_POST['add_medication_ID']) ? $_POST['add_medication_ID'] : "";
        $value_add_dosage = isset($_POST['add_dosage']) ? $_POST['add_dosage'] : "";
        $value_add_regime = isset($_POST['add_regime']) ? $_POST['add_regime'] : "";

        $value_rm_medication = isset($_POST['rm_medication']) ? $_POST['rm_medication'] : "";
        if (empty($value_rm_medication)) {
            $value_rm_medication_name = "";
            $value_rm_medication_lab = "";
            $value_rm_medication_ID = "";
        } else {
            $pieces = explode(", ", $value_rm_medication);
            $value_rm_medication_name = $pieces[0];
            $value_rm_medication_lab = $pieces[1];
            $value_rm_medication_ID = $pieces[2];
        }

        $value_add_nurse = isset($_POST['add_nurse']) ? $_POST['add_nurse'] : "";
        $value_rm_nurse = isset($_POST['rm_nurse']) ? $_POST['rm_nurse'] : "";

        $value_add_procedure_name = isset($_POST['add_procedure_name']) ? $_POST['add_procedure_name'] : "";
        $value_add_procedure_description = isset($_POST['add_procedure_description']) ? $_POST['add_procedure_description'] : "";
        $value_rm_procedure = isset($_POST['rm_procedure']) ? $_POST['rm_procedure'] : "";

        $value_save_SOAP_S = isset($_POST['save_SOAP_S']) ? $_POST['save_SOAP_S'] : "";
        $value_save_SOAP_O = isset($_POST['save_SOAP_O']) ? $_POST['save_SOAP_O'] : "";
        $value_save_SOAP_A = isset($_POST['save_SOAP_A']) ? $_POST['save_SOAP_A'] : "";
        $value_save_SOAP_P = isset($_POST['save_SOAP_P']) ? $_POST['save_SOAP_P'] : "";

        $result_consultation = null;
        $result_nurse = null;
        $result_available_nurses = null;
        $result_diagnostic = null;
        $result_available_diagnostic = null;
        $result_prescription = null;
        $result_available_prescription = null;
        $result_procedure = null;
        $result_available_procedures = null;

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

        $query_consultation = "SELECT client.name, client.VAT, client.gender, client.age, employee.name as drname, doctor.specialization, appointment.description, consultation.* 
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
        AND consultation_diagnostic.date_timestamp = ?
        ORDER BY consultation_diagnostic.ID ASC";

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
        AND consultation_diagnostic.date_timestamp = ?)
        ORDER BY diagnostic_code.ID ASC";

        $query_rm_prescription = "DELETE FROM prescription
        WHERE VAT_doctor = ?
        AND date_timestamp = ?
        AND ID = ?
        AND name = ?
        AND lab = ?";

        $query_add_prescription = "INSERT INTO prescription (name, lab, VAT_doctor, date_timestamp, ID, dosage, description)
        VALUES (?, ? , ?, ?, ?, ?, ?)";

        $query_available_prescription = "SELECT * 
        FROM medication
        ORDER BY name ASC";

        $query_rm_nurse = "DELETE FROM consultation_assistant
        WHERE VAT_doctor = ?
        AND date_timestamp = ?
        AND VAT_nurse = ?";

        $query_add_nurse = "INSERT INTO consultation_assistant (VAT_doctor, date_timestamp, VAT_nurse)
        VALUES (?, ? , ?)";

        $query_nurse = "SELECT *
        FROM consultation_assistant
        INNER JOIN employee
        ON consultation_assistant.VAT_nurse = employee.VAT
        WHERE consultation_assistant.VAT_doctor = ?
        AND consultation_assistant.date_timestamp = ?
        ORDER BY employee.name ASC";

        $query_available_nurses = "SELECT * 
        FROM nurse
        INNER JOIN employee
        ON nurse.VAT = employee.VAT
        WHERE nurse.VAT NOT IN (
        SELECT consultation_assistant.VAT_nurse
        FROM consultation_assistant
        WHERE consultation_assistant.VAT_doctor = ?
        AND consultation_assistant.date_timestamp = ?)
        ORDER BY employee.name ASC";

        $query_prescription = "SELECT *
        FROM prescription
        WHERE VAT_doctor = ?
        AND date_timestamp = ?
        ORDER BY name ASC";

        $query_procedure = "SELECT *
        FROM procedure_
        INNER JOIN procedure_in_consultation
        ON procedure_.name = procedure_in_consultation.name
        WHERE procedure_in_consultation.VAT_doctor = ?
        AND procedure_in_consultation.date_timestamp = ?
        ORDER BY procedure_.type ASC";

        $query_add_procedure = "INSERT INTO procedure_in_consultation (VAT_doctor, date_timestamp, name, description)
        VALUES (?, ? , ?, ?)";

        $query_rm_procedure = "DELETE FROM procedure_in_consultation
        WHERE VAT_doctor = ?
        AND date_timestamp = ?
        AND name = ?";

        $query_available_procedure = "SELECT * 
        FROM procedure_
        WHERE name NOT IN (
        SELECT name
        FROM procedure_in_consultation
        WHERE VAT_doctor = ?
        AND date_timestamp = ?)
        ORDER BY name ASC";

        if (!empty($value_save_SOAP_S)) {
            $stmt = $dbh->prepare($query_save_SOAP_S);
            $stmt->bindParam(1, $value_save_SOAP_S);
            $stmt->bindParam(2, $value_VAT);
            $stmt->bindParam(3, $value_timestamp);
            if (!$stmt->execute()) {
                print("Something went wrong when saving the SOAP\n");
            }
        }

        if (!empty($value_save_SOAP_O)) {
            $stmt = $dbh->prepare($query_save_SOAP_O);
            $stmt->bindParam(1, $value_save_SOAP_O);
            $stmt->bindParam(2, $value_VAT);
            $stmt->bindParam(3, $value_timestamp);
            if (!$stmt->execute()) {
                print("Something went wrong when saving the SOAP\n");
            }
        }

        if (!empty($value_save_SOAP_A)) {
            $stmt = $dbh->prepare($query_save_SOAP_A);
            $stmt->bindParam(1, $value_save_SOAP_A);
            $stmt->bindParam(2, $value_VAT);
            $stmt->bindParam(3, $value_timestamp);
            if (!$stmt->execute()) {
                print("Something went wrong when saving the SOAP\n");
            }
        }

        if (!empty($value_save_SOAP_P)) {
            $stmt = $dbh->prepare($query_save_SOAP_P);
            $stmt->bindParam(1, $value_save_SOAP_P);
            $stmt->bindParam(2, $value_VAT);
            $stmt->bindParam(3, $value_timestamp);
            if (!$stmt->execute()) {
                print("Something went wrong when saving the SOAP\n");
            }
        }


        $stmt = $dbh->prepare($query_consultation);
        $stmt->bindParam(1, $value_VAT);
        $stmt->bindParam(2, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the client data");
        } else {
            if ($stmt->rowCount() > 0) {
                $result_consultation = $stmt->fetch();
            }
        }

        if (!empty($value_rm_nurse)) {
            $stmt = $dbh->prepare($query_rm_nurse);
            $stmt->bindParam(1, $value_VAT);
            $stmt->bindParam(2, $value_timestamp);
            $stmt->bindParam(3, $value_rm_nurse);
            if (!$stmt->execute()) {
                print("Something went wrong when removing nurse");
            }
        }

        if (!empty($value_add_nurse)) {
            $stmt = $dbh->prepare($query_add_nurse);
            $stmt->bindParam(1, $value_VAT);
            $stmt->bindParam(2, $value_timestamp);
            $stmt->bindParam(3, $value_add_nurse);
            if (!$stmt->execute()) {
                print("Something went wrong when adding nurse");
            }
        }

        $stmt = $dbh->prepare($query_nurse);
        $stmt->bindParam(1, $value_VAT);
        $stmt->bindParam(2, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the nurses involved");
        } else {
            if ($stmt->rowCount() > 0) {
                $result_nurse = $stmt->fetchAll();
            }
        }

        $stmt = $dbh->prepare($query_available_nurses);
        $stmt->bindParam(1, $value_VAT);
        $stmt->bindParam(2, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the available nurses");
        } else {
            if ($stmt->rowCount() > 0) {
                $result_available_nurses = $stmt->fetchAll();
            }
        }

        if (!empty($value_rm_diagnostic)) {
            $stmt = $dbh->prepare($query_rm_diagnostic);
            $stmt->bindParam(1, $value_VAT);
            $stmt->bindParam(2, $value_timestamp);
            $stmt->bindParam(3, $value_rm_diagnostic);
            if (!$stmt->execute()) {
                print("Something went wrong when removing diagnostic");
            }
        }

        if (!empty($value_add_diagnostic)) {
            $stmt = $dbh->prepare($query_add_diagnostic);
            $stmt->bindParam(1, $value_VAT);
            $stmt->bindParam(2, $value_timestamp);
            $stmt->bindParam(3, $value_add_diagnostic);
            if (!$stmt->execute()) {
                print("Something went wrong when adding diagnostic");
            }
        }

        $stmt = $dbh->prepare($query_diagnostic);
        $stmt->bindParam(1, $value_VAT);
        $stmt->bindParam(2, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the diagnostic codes");
        } else {
            if ($stmt->rowCount() > 0) {
                $result_diagnostic = $stmt->fetchAll();
            }
        }

        $stmt = $dbh->prepare($query_available_diagnostic);
        $stmt->bindParam(1, $value_VAT);
        $stmt->bindParam(2, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the available diagnostic codes");
        } else {
            if ($stmt->rowCount() > 0) {
                $result_available_diagnostic = $stmt->fetchAll();
            }
        }

        if (!empty($value_rm_medication)) {
            $stmt = $dbh->prepare($query_rm_prescription);
            $stmt->bindParam(1, $value_VAT);
            $stmt->bindParam(2, $value_timestamp);
            $stmt->bindParam(3, $value_rm_medication_ID);
            $stmt->bindParam(4, $value_rm_medication_name);
            $stmt->bindParam(5, $value_rm_medication_lab);
            if (!$stmt->execute()) {
                print($stmt->error);
                print("Something went wrong when removing prescription");
            }
        }

        if (!empty($value_add_medication_name) && !empty($value_add_medication_lab) && !empty($value_add_dosage) && !empty($value_add_regime)) {
            $stmt = $dbh->prepare($query_add_prescription);
            $stmt->bindParam(1, $value_add_medication_name);
            $stmt->bindParam(2, $value_add_medication_lab);
            $stmt->bindParam(3, $value_VAT);
            $stmt->bindParam(4, $value_timestamp);
            $stmt->bindParam(5, $value_add_prescription_ID);
            $stmt->bindParam(6, $value_add_dosage);
            $stmt->bindParam(7, $value_add_regime);
            if (!$stmt->execute()) {
                //print($dbh->error);
            }
        }


        $stmt = $dbh->prepare($query_prescription);
        $stmt->bindParam(1, $value_VAT);
        $stmt->bindParam(2, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the prescriptions");
        } else {
            if ($stmt->rowCount() > 0) {
                $result_prescription = $stmt->fetchAll();
            }
        }

        $stmt = $dbh->prepare($query_available_prescription);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the available medication");
        } else {
            if ($stmt->rowCount() > 0) {
                $result_available_prescription = $stmt->fetchAll();
            }
        }


        echo "<datalist id='medication'>";
        foreach ($result_available_prescription as &$medication) {
            echo "<option value=\"" . $medication['name'] . ", " . $medication['lab'] . "\"></option>";
        }
        echo "</datalist>";

        if (!empty($value_rm_procedure)) {
            $stmt = $dbh->prepare($query_rm_procedure);
            $stmt->bindParam(1, $value_VAT);
            $stmt->bindParam(2, $value_timestamp);
            $stmt->bindParam(3, $value_rm_procedure);
            if (!$stmt->execute()) {
                print("Something went wrong when removing procedure");
            }
        }

        if (!empty($value_add_procedure_name) && !empty($value_add_procedure_description)) {
            $stmt = $dbh->prepare($query_add_procedure);
            $stmt->bindParam(1, $value_VAT);
            $stmt->bindParam(2, $value_timestamp);
            $stmt->bindParam(3, $value_add_procedure_name);
            $stmt->bindParam(4, $value_add_procedure_description);
            if (!$stmt->execute()) {
                print($stmt->error);
                print("Something went wrong when adding procedure");
            }
        }

        $stmt = $dbh->prepare($query_procedure);
        $stmt->bindParam(1, $value_VAT);
        $stmt->bindParam(2, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the prescriptions");
        } else {
            if ($stmt->rowCount() > 0) {
                $result_procedure = $stmt->fetchAll();
            }
        }

        $stmt = $dbh->prepare($query_available_procedure);
        $stmt->bindParam(1, $value_VAT);
        $stmt->bindParam(2, $value_timestamp);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the available nurses");
        } else {
            if ($stmt->rowCount() > 0) {
                $result_available_procedures = $stmt->fetchAll();
            }
        }


        if ($result_consultation != null) {

            echo "<div style='width: 100%; text-align:center'>
            <button name='' value='' style='font-size:2em;' onclick=\"location.href='" . $db->url() . "client.php?VAT=" . $result_consultation["VAT"] . "'\"><</button>
            <button name='' value='' style='font-size:2em;' onclick=\"location.href='" . $db->url() . "clients.php'\">&#127968;</button>
            <button name='' value='' style='visibility:hidden;font-size:2em;'><</button>
            </div><br>";

            echo "<div class='wrapper'>
            <div class='container'>
            <div class='one'>
            <img id='profileImage' src='http://web.tecnico.ulisboa.pt/ist425108/SIBD/images/profile/" . $result_consultation["gender"] . ".png'/>
            </div>
            <div class='two' style='text-align:left'>";

            echo "<h1>" . $result_consultation["name"] . "</h1>";
            echo "<h4>Gender:</h4> " . $result_consultation["gender"];
            echo "<br>";
            echo "<h4>Age:</h4> " . $result_consultation["age"];

            echo "</div>
            </div>
            <div class='container'>
            <div class='two' style='text-align: right'>";

            echo "<h1>" . $result_consultation["drname"] . "</h1>";
            echo $result_consultation["specialization"] . " doctor";

            echo "</div>
            <div class='one'>
            <img id='profileImage' src='http://web.tecnico.ulisboa.pt/ist425108/SIBD/images/profile/doctor.png'/>
            </div></div></div>";

            echo "<br><h1>Consultation Details</h1>";
            echo "<h4>Date:</h4> " . $result_consultation["date_timestamp"];
            echo "<br>";
            echo "<h4>Description:</h4> " . $result_consultation["description"];
            echo "<br><br><br>";

            echo "<form action='' id='save_SOAP' method='post'>
            <h3>Consultation Notes </h3>
            <button name='' value='' style='background:#B0C4DE'>&#128190;</button>
            </form>";
            echo "<h4>Subjective</h4><br>";
            echo "<textarea maxlength='512' wrap='hard' name='save_SOAP_S' form='save_SOAP'>" . $result_consultation["SOAP_S"] . "</textarea><br>";
            echo "<br>";
            echo "<h4>Objective</h4><br>";
            echo "<textarea maxlength='512' wrap='hard' name='save_SOAP_O' form='save_SOAP'>" . $result_consultation["SOAP_O"] . "</textarea><br>";
            echo "<br>";
            echo "<h4>Assessment</h4><br>";
            echo "<textarea maxlength='512' wrap='hard' name='save_SOAP_A' form='save_SOAP'>" . $result_consultation["SOAP_A"] . "</textarea><br>";
            echo "<br>";
            echo "<h4>Plan</h4><br>";
            echo "<textarea maxlength='512' wrap='hard' name='save_SOAP_P' form='save_SOAP'>" . $result_consultation["SOAP_P"] . "</textarea><br>";
            echo "<br>";
        } else {
            echo "<script>location.href='" . $db->url() . "clients.php'</script>";
        }

        echo "<br>";
        echo "<h3>Nurse(s) Assisting</h3><br><br>";
        if ($result_nurse != null) {
            echo ("<table>\n");
            echo ("<tr class='header'><td>Name</td><td>&#128465;</td></tr>\n");
            foreach ($result_nurse as &$nurse) {
                echo "<tr class='row'><td>" . $nurse['name'] . "</td>
                <td>
                    <form action='' method='post'>
                        <button name='rm_nurse' value='" . $nurse['VAT'] . "' style='background:red; color:white'>&#10008;</button>
                    </form>
                </td>
                </tr>\n";
            }
            echo ("</table>\n");
        } else {
            echo "No nurses.<br>";
        }
        if ($result_available_nurses != null) {
            echo "<br><form action='' method='post'>
            <input list='nurses' name='add_nurse' autocomplete='off' required>
            <datalist id='nurses'>";
            foreach ($result_available_nurses as &$nurse) {
                echo "<option value=\"" . $nurse['VAT'] . "\">" . $nurse['name'] . "</option>";
            }
            echo "</datalist>
            <button name='' value='' style='background:green; color:white'>&#10010;</button>
            </form>";
        }

        echo "<br><br><h3>Diagnostic(s)</h3><br><br>";
        if ($result_diagnostic != null) {
            echo ("<table>\n");
            echo ("<tr class='header'><td>Diagnostic Code</td><td>Description</td><td>&#128465;</td><td>Medication</td></tr>\n");
            foreach ($result_diagnostic as &$diagnostic) {
                echo "<tr>
                <td>" . $diagnostic['ID'] . "</td>
                <td>" . $diagnostic['description'] . "</td>
                <td>
                    <form action='' method='post'>
                        <button name='rm_diagnostic' value='" . $diagnostic['ID'] . "' style='background:red; color:white'>&#10008;</button>
                    </form>
                </td>
                <td>
                <button id='" . $diagnostic['ID'] . "' onclick='promptPrescription(\"" . $diagnostic['ID'] . "\")' style='background:green; color:white'>&#10010;</button>
                </td>
                </tr>\n";
            }
            echo ("</table>\n");
        } else {
            echo "No diagnostic.<br>";
        }

        if ($result_available_diagnostic != null) {
            echo "<br><form action='' method='post'>
            <input list='diagnostics' name='add_diagnostic' autocomplete='off' required>
            <datalist id='diagnostics'>";
            foreach ($result_available_diagnostic as &$diagnostic_code) {
                echo "<option value=\"" . $diagnostic_code['ID'] . "\">" . $diagnostic_code['description'] . "</option>";
            }
            echo "</datalist>
            <button name='' value='' style='background:green; color:white'>&#10010;</button>
            </form>";
        }

        echo "<br><br><h3>Prescription(s)</h3><br><br>";
        if ($result_prescription != null) {
            echo ("<table>\n");
            echo ("<tr class='header'><td>Name</td><td>Lab</td><td>Diagnostic</td><td>Dosage</td><td>Regime</td><td>&#128465;</td></tr>\n");
            foreach ($result_prescription as &$prescription) {
                echo "<tr><td>" . $prescription['name'] . "</td><td>" . $prescription['lab'] . "</td><td>" . $prescription['ID'] . "</td><td>" . $prescription['dosage'] . "</td><td>" . $prescription['description'] . "</td>
                <td>
                    <form action='' method='post'>
                        <button name='rm_medication' value='" . $prescription['name'] . ", " . $prescription['lab'] . ", " . $prescription['ID'] . "' style='background:red; color:white'>&#10008;</button>
                    </form>
                </td>
                </tr>\n";
            }
            echo ("</table>\n");
        } else {
            echo "No medication.";
        }

        echo "<br><br><h3>Procedure(s)</h3><br><br>";
        if ($result_procedure != null) {
            echo ("<table>\n");
            echo ("<tr class='header'><td>Type</td><td>Name</td><td>Description</td><td></td><td>&#128465;</td></tr>\n");
            foreach ($result_procedure as &$procedure) {
                echo "<tr><td>" . $procedure['type'] . "</td><td>" . $procedure['name'] . "</td><td>" . $procedure['description'] . "</td>
                <td> " . (($procedure["type"] == "Dental chartings") ? "<button onclick=\"location.href = '" . $db->url() . "charting.php?VAT=$value_VAT&timestamp=$value_timestamp'\" name='' value=''>&#x1F50D;</button>" : "")
                    . "</td>
                <td>
                    <form action='' method='post'>
                        <button name='rm_procedure' value='" . $procedure['name'] . "' style='background:red; color:white'>&#10008;</button>
                    </form>
                </td>
                </tr>\n";
            }
            echo ("</table>\n");
        } else {
            echo "No procedures.";
        }
    } else {
        echo "<script>location.href='" . $db->url() . "clients.php'</script>";
    }

    if ($result_available_procedures != null) {
        echo "<br><form action='' method='post'>
        <input list='procedures' name='add_procedure_name' autocomplete='off' required id='procedure_name' oninput='checkProcedure()'>
        <datalist id='procedures'>";
        foreach ($result_available_procedures as &$procedure) {
            echo "<option value=\"" . $procedure['name'] . "\">" . $procedure['type'] . "</option>";
        }
        echo "</datalist>
        <button name='' value='' style='background:green; color:white'>&#10010;</button><br><br>
        <div id='procedure_text' style='visibility:hidden'><label for='procedure_description'><h4>Description</h4></label><br><textarea rows='4' id='procedure_description' cols='100' maxlength='255' wrap='hard' name='add_procedure_description' required></textarea></div>
        </form>";
    }

    $dbh = null;
    ?>

    <script>
        function checkProcedure() {
            if (document.getElementById("procedure_name").value === "") {
                document.getElementById("procedure_text").style.visibility = 'hidden';
            } else {
                document.getElementById("procedure_text").style.visibility = 'visible';
            }
            return;
        }



        function promptPrescription(ID) {
            document.getElementById("popupPrescription").style.display = "block";
            var left = window.scrollX + document.getElementById(ID).getBoundingClientRect().left;
            var top = window.scrollY + document.getElementById(ID).getBoundingClientRect().top;
            document.getElementById("popupPrescription").style.left = left - 280;
            document.getElementById("popupPrescription").style.top = top;
            document.getElementById("add_medication_ID").value = ID;
        }
    </script>

    <div id="popupPrescription">
        <div style="float:right"><button onclick="document.getElementById('popupPrescription').style.display = 'none';">X</button></div><br><br>
        <div>
            <form action='' method='post'>
                <input type="text" name="add_medication_ID" id="add_medication_ID" style="display:none">
                <label for="add_medication">Medication: </label>
                <input list='medication' name='add_medication' id='add_medication' required style="width:200px"><br>
                <label for="dosage">Dosage: </label>
                <input name="add_dosage" id="dosage" type="text" placeholder="e.g. 1000 mg" maxlength="32" required oninvalid="setCustomValidity('Invalid Dosage')" oninput="setCustomValidity('')" style="width:200px"><br>
                <label for="regime">Regime: </label>
                <input name="add_regime" id="regime" type="text" placeholder="e.g. 1 pill every day" maxlength="64" required oninvalid="setCustomValidity('Invalid Regime')" oninput="setCustomValidity('')" style="width:200px"><br>
                <br>
                <div style="width: 100%; text-align: center">
                    <button name='' value=''>SUBMIT</button>
                </div>
            </form>
        </div>
    </div>
    <div style="left:0;width:100%;height:20px;position:fixed;z-index:99;bottom:0;text-align:center">
        <span style="background:rgba(150,150,150,0.5)">SIBD - Project Part 3 - Group 50</span>
    </div>
</body>

</html>