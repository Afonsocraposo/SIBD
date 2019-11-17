/* INDEX 1 */

create index indexName on employee(name);

EXPLAIN EXTENDED SELECT DISTINCT client.VAT as "Client VAT", client.name as "Name", phone_number_client.phone as "Phone"
FROM client, phone_number_client, appointment, consultation, employee USE INDEX(indexName)
WHERE appointment.date_timestamp = consultation.date_timestamp
    AND employee.name = 'Jane Sweettooth'
    AND employee.VAT = appointment.VAT_doctor
    AND client.VAT = appointment.VAT_client
    AND phone_number_client.VAT = client.VAT;


/* INDEX 2 */

create index indexScore on supervision_report(evaluation);
create FULLTEXT INDEX ON supervision_report(description);
/* Change query to CONTAINS(supervision_report.description, "insufficient") */


EXPLAIN EXTENDED SELECT etd.name AS "Trainee Dr Name", td.VAT AS "Trainee Dr VAT", epd.name AS "Permanent Dr Name", supervision_report.evaluation AS "Evaluation Score", supervision_report.description AS "Report Description"
FROM (employee etd INNER JOIN trainee_doctor as td ON etd.VAT = td.VAT) ,
    (employee epd INNER JOIN permanent_doctor as pd ON epd.VAT = pd.VAT),
    supervision_report
WHERE
(supervision_report.evaluation < 3 OR LOWER
(supervision_report.description) LIKE '%insufficient%')
	AND td.VAT = supervision_report.VAT
    AND td.supervisor = pd.VAT
ORDER BY supervision_report.evaluation DESC;
