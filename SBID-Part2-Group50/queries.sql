/* QUERY 1 */
SELECT DISTINCT client.VAT as "Client VAT", client.name as "Name", phone_number_client.phone as "Phone"
FROM client, phone_number_client, appointment, consultation, employee
WHERE appointment.date_timestamp = consultation.date_timestamp
    AND employee.name = 'Jane Sweettooth'
    AND employee.VAT = appointment.VAT_doctor
    AND client.VAT = appointment.VAT_client
    AND phone_number_client.VAT = client.VAT
ORDER BY client.name ASC;

/* QUERY 2 */
SELECT etd.name AS "Trainee Dr Name", td.VAT AS "Trainee Dr VAT", epd.name AS "Permanent Dr Name", supervision_report.evaluation AS "Evaluation Score", supervision_report.description AS "Report Description"
FROM (employee etd INNER JOIN trainee_doctor td ON etd.VAT = td.VAT) ,
    (employee epd INNER JOIN permanent_doctor pd ON epd.VAT = pd.VAT),
    supervision_report
WHERE (supervision_report.evaluation < 3 OR LOWER(supervision_report.description) LIKE '%insufficient%')
    AND td.VAT = supervision_report.VAT
    AND td.supervisor = pd.VAT
ORDER BY supervision_report.evaluation DESC;

/* QUERY 3 */
SELECT client.name as "Client Name", client.city as "City", client.VAT as "VAT"
FROM client,
    (SELECT appointment.VAT_client, consultation.SOAP_O, max(appointment.date_timestamp)
    FROM (appointment
        INNER JOIN consultation
        ON appointment.date_timestamp = consultation.date_timestamp
            AND appointment.VAT_doctor = consultation.VAT_doctor)
    GROUP BY appointment.VAT_client) as con
WHERE (LOWER(con.SOAP_O) LIKE '%gingivitis%' OR LOWER(con.SOAP_O) like '%periodontitis%')
    AND con.VAT_client = client.VAT;

/* QUERY 4 */
SELECT DISTINCT client.name as "Client Name", client.VAT as "VAT", client.city as "City", client.street as "Street", client.zip as "ZIP code"
FROM client
    INNER JOIN appointment
    ON appointment.VAT_client = client.VAT
WHERE VAT_client NOT IN (SELECT DISTINCT appointment.VAT_client
FROM appointment
    INNER JOIN consultation
    ON appointment.date_timestamp = consultation.date_timestamp
        AND appointment.VAT_doctor = consultation.VAT_doctor);

/* QUERY 5 */
SELECT diagnostic_code.ID as "Diagnostic Code", diagnostic_code.description as "Description", COUNT(DISTINCT medication.name) as "Number of distinct medications"
FROM diagnostic_code
    LEFT JOIN (prescription
    INNER JOIN medication
    ON (prescription.name = medication.name
        AND prescription.lab = medication.lab))
    ON diagnostic_code.ID = prescription.ID
GROUP BY(diagnostic_code.ID)
ORDER BY COUNT(DISTINCT medication.name) asc;

/* QUERY 6 */
SELECT Age as "0: <=18 | 1: >18", AVG(temp.count_nurses) as "Average Nurses", AVG(temp.count_procedures) as "Average Procedures", AVG(temp.count_diagnostics) as "Average Diagnostics", AVG(temp.count_prescriptions) as "Average Prescriptions"
FROM
    (SELECT client.age>18 as Age, COUNT(DISTINCT consultation_assistant.VAT_nurse) as count_nurses, COUNT(DISTINCT procedure_in_consultation.name) as count_procedures, COUNT(DISTINCT consultation_diagnostic.ID) as count_diagnostics, COUNT(DISTINCT prescription.name, prescription.lab) as count_prescriptions
    FROM client
        INNER JOIN appointment
        ON (client.VAT = appointment.VAT_client)
        INNER JOIN consultation
        ON (appointment.VAT_doctor = consultation.VAT_doctor
            AND appointment.date_timestamp = consultation.date_timestamp)
        LEFT JOIN consultation_assistant
        ON (consultation.VAT_doctor = consultation_assistant.VAT_doctor
            AND consultation.date_timestamp = consultation_assistant.date_timestamp)
        LEFT JOIN procedure_in_consultation
        ON (consultation.VAT_doctor = procedure_in_consultation.VAT_doctor
            AND consultation.date_timestamp = procedure_in_consultation.date_timestamp)
        LEFT JOIN consultation_diagnostic
        ON (consultation.VAT_doctor = consultation_diagnostic.VAT_doctor
            AND consultation.date_timestamp = consultation_diagnostic.date_timestamp)
        LEFT JOIN prescription
        ON (consultation.VAT_doctor = prescription.VAT_doctor
            AND consultation.date_timestamp = prescription.date_timestamp)
    WHERE year(consultation.date_timestamp) = 2019
    GROUP BY consultation.VAT_doctor, consultation.date_timestamp) as temp
GROUP BY Age;


/* QUERY 7 */
SELECT temp.ID as "Diagnostic Code", temp.name "Most used medication"
FROM
    (SELECT diagnostic_code.ID, medication.name
    FROM diagnostic_code
        LEFT JOIN (prescription
        INNER JOIN medication
        ON (prescription.name = medication.name
            AND prescription.lab = medication.lab)
        )
        ON diagnostic_code.ID = prescription.ID
    GROUP by diagnostic_code.ID, medication.name
    ORDER BY COUNT(medication.name) DESC) AS temp
GROUP by temp.ID;

/* GET COUNT FOR EACH MEDICATION AND DC.ID */
SELECT diagnostic_code.ID, medication.name, COUNT(medication.name)
FROM diagnostic_code
    LEFT JOIN (prescription
    INNER JOIN medication
    ON (prescription.name = medication.name
        AND prescription.lab = medication.lab)
    )
    ON diagnostic_code.ID = prescription.ID
GROUP by diagnostic_code.ID, medication.name;

/* QUERY 8 */
SELECT DISTINCT prescription.name as "Medication Name", prescription.lab as "Lab"
FROM prescription, diagnostic_code
WHERE prescription.ID = diagnostic_code.ID
    AND diagnostic_code.description LIKE '%dental cavities%'
    AND diagnostic_code.description NOT LIKE '%infectious disease%'
    AND YEAR(prescription.date_timestamp) = 2019
ORDER BY prescription.name;

/* QUERY 9 */
SELECT DISTINCT client.name as "Client Name", client.city as "City", client.street as "Street", client.zip as "ZIP code"
FROM client
    INNER JOIN appointment
    ON appointment.VAT_client = client.VAT
WHERE YEAR(appointment.date_timestamp) = 2019
    AND VAT_client IN (SELECT DISTINCT appointment.VAT_client
    FROM appointment
        INNER JOIN consultation
        ON appointment.date_timestamp = consultation.date_timestamp
            AND appointment.VAT_doctor = consultation.VAT_doctor);