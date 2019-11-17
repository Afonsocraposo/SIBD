/* Change 1 */
select *
from employee
WHERE name = "Jane Sweettooth";

UPDATE employee
SET city = "Santiago do Cacem", street = "Rua de Mirobriga", zip = "7540-109"
WHERE name = "Jane Sweettooth";

select *
from employee
WHERE name = "Jane Sweettooth";

/* Change 2 */

SELECT VAT, salary, COUNT(appointment.date_timestamp) as "Appointments"
FROM employee
    INNER JOIN appointment
    ON employee.VAT = appointment.VAT_doctor
WHERE YEAR(appointment.date_timestamp) = 2019
GROUP BY employee.VAT;

UPDATE employee,
    (SELECT VAT
FROM employee
    INNER JOIN appointment
    ON employee.VAT = appointment.VAT_doctor
WHERE YEAR(appointment.date_timestamp) = 2019
GROUP BY employee.VAT
HAVING COUNT(appointment.date_timestamp)>100)
as raise
SET salary
= salary*1.05
WHERE employee.VAT = raise.VAT;

SELECT VAT, salary, COUNT(appointment.date_timestamp) as "Appointments"
FROM employee
    INNER JOIN appointment
    ON employee.VAT = appointment.VAT_doctor
WHERE YEAR(appointment.date_timestamp) = 2019
GROUP BY employee.VAT;

/* Change 3 */
SELECT COUNT(DISTINCT appointment.date_timestamp) as "Total Appointments", COUNT(DISTINCT consultation.date_timestamp) as "Total Consultations"
FROM employee
    INNER JOIN appointment
    ON (employee.VAT = appointment.VAT_doctor)
    INNER JOIN consultation
    ON appointment.VAT_doctor = consultation.VAT_doctor;

DELETE FROM diagnostic_code
WHERE ID IN (
    SELECT consultation_diagnostic.ID
FROM appointment
    INNER JOIN consultation
    ON appointment.date_timestamp = consultation.date_timestamp
        AND appointment.VAT_doctor = consultation.VAT_doctor
    INNER JOIN consultation_diagnostic
    ON consultation.date_timestamp = consultation_diagnostic.date_timestamp
        AND consultation.VAT_doctor = consultation_diagnostic.VAT_doctor
    LEFT JOIN employee
    ON employee.VAT = consultation.VAT_doctor
        AND employee.name NOT LIKE "Jane Sweettooth"
GROUP BY consultation_diagnostic.ID
HAVING COUNT(DISTINCT employee.VAT) = 0)

DELETE FROM procedure_
WHERE name IN (
    SELECT procedure_in_consultation.name
FROM appointment
    INNER JOIN consultation
    ON appointment.date_timestamp = consultation.date_timestamp
        AND appointment.VAT_doctor = consultation.VAT_doctor
    INNER JOIN procedure_in_consultation
    ON consultation.date_timestamp = procedure_in_consultation.date_timestamp
        AND consultation.VAT_doctor = procedure_in_consultation.VAT_doctor
    LEFT JOIN employee
    ON employee.VAT = consultation.VAT_doctor
        AND employee.name NOT LIKE "Jane Sweettooth"
GROUP BY procedure_in_consultation.name
HAVING COUNT(DISTINCT employee.VAT) = 0)

DELETE FROM employee
WHERE name = "Jane Sweettooth";

SELECT COUNT(DISTINCT appointment.date_timestamp) as "Total Appointments", COUNT(DISTINCT consultation.date_timestamp) as "Total Consultations"
FROM employee
    INNER JOIN appointment
    ON (employee.VAT = appointment.VAT_doctor)
    INNER JOIN consultation
    ON appointment.VAT_doctor = consultation.VAT_doctor;

/* Change 4 */

/* Add Diagnostic Code if it doesn't exist */
INSERT INTO diagnostic_code
    (ID,description)
SELECT *
FROM (SELECT 'DC_11', 'Periodontitis') AS tmp
WHERE NOT EXISTS (
    SELECT description
FROM diagnostic_code
WHERE LOWER(description) LIKE '%periodontitis%'
)
LIMIT 1;

/* Check AVG(measure)>4 */
SELECT consultation_diagnostic.ID, diagnostic_code.description, AVG(procedure_charting.measure) as "Average Gap"
FROM consultation_diagnostic
    INNER JOIN diagnostic_code
    ON consultation_diagnostic.ID = diagnostic_code.ID
    INNER JOIN procedure_charting
    ON consultation_diagnostic.date_timestamp = procedure_charting.date_timestamp
        AND consultation_diagnostic.VAT_doctor = procedure_charting.VAT
GROUP BY procedure_charting.VAT, procedure_charting.date_timestamp
HAVING AVG(procedure_charting.measure)>4;

/* UPDATE */
UPDATE consultation_diagnostic,
    (SELECT consultation_diagnostic.date_timestamp, consultation_diagnostic.VAT_doctor
FROM consultation_diagnostic
    INNER JOIN diagnostic_code
    ON consultation_diagnostic.ID = diagnostic_code.ID
    INNER JOIN procedure_charting
    ON consultation_diagnostic.date_timestamp = procedure_charting.date_timestamp
        AND consultation_diagnostic.VAT_doctor = procedure_charting.VAT
WHERE LOWER(diagnostic_code.description) LIKE '%gingivitis%'
GROUP BY procedure_charting.VAT, procedure_charting.date_timestamp
HAVING AVG(procedure_charting.measure)>4)
as consultations
SET consultation_diagnostic
.ID =
(SELECT DISTINCT ID
FROM diagnostic_code
WHERE LOWER(description) LIKE '%periodontitis%'
LIMIT 1)
WHERE consultation_diagnostic.date_timestamp = consultations.date_timestamp
AND consultation_diagnostic.VAT_doctor = consultations.VAT_doctor;

/* Check AVG(measure)>4 */
SELECT consultation_diagnostic.ID, diagnostic_code.description, AVG(procedure_charting.measure) as "Average Gap"
FROM consultation_diagnostic
    INNER JOIN diagnostic_code
    ON consultation_diagnostic.ID = diagnostic_code.ID
    INNER JOIN procedure_charting
    ON consultation_diagnostic.date_timestamp = procedure_charting.date_timestamp
        AND consultation_diagnostic.VAT_doctor = procedure_charting.VAT
GROUP BY procedure_charting.VAT, procedure_charting.date_timestamp
HAVING AVG(procedure_charting.measure)>4;

/* Check diagnostic codes */
SELECT *
FROM diagnostic_code;