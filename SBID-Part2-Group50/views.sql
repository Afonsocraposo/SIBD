/* View 1 */
CREATE OR REPLACE VIEW dim_date AS
SELECT consultation.date_timestamp AS date_timestamp, YEAR(consultation.date_timestamp) AS "Year", MONTH(consultation.date_timestamp) AS "Month", DAY(consultation.date_timestamp) as "Day"
FROM consultation;

SELECT *
FROM dim_date;

/* View 2 */
CREATE OR REPLACE  VIEW dim_client AS
SELECT client.VAT AS VAT, client.gender AS "Gender", client.age AS "Age"
FROM client;

SELECT *
FROM dim_client;

/* View 3 */
CREATE OR REPLACE  VIEW dim_location_client AS
SELECT DISTINCT client.zip AS ZIP, client.city AS "City"
FROM client;

SELECT *
FROM dim_location_client;

/* View 4 */
CREATE OR REPLACE VIEW facts_consults AS
SELECT dim_client.VAT as "Client VAT", dim_date.date_timestamp as "Date", dim_location_client.ZIP AS "ZIP", COUNT(DISTINCT procedure_in_consultation.name) AS "# Procedures", COUNT(DISTINCT prescription.name, prescription.lab) AS "# Medication", COUNT(DISTINCT consultation_diagnostic.ID) AS "# Diagnostic Codes"
FROM dim_client
	INNER JOIN client
	ON dim_client.VAT = client.VAT
	INNER JOIN dim_location_client
	ON client.zip = dim_location_client.zip
	INNER JOIN appointment
	ON dim_client.VAT = appointment.VAT_client
	INNER JOIN dim_date
	ON appointment.date_timestamp = dim_date.date_timestamp
	INNER JOIN consultation
	ON appointment.date_timestamp = consultation.date_timestamp
		AND appointment.VAT_doctor = consultation.VAT_doctor
	LEFT JOIN consultation_diagnostic
	ON consultation.date_timestamp = consultation_diagnostic.date_timestamp
		AND consultation.VAT_doctor = consultation_diagnostic.VAT_doctor
	LEFT JOIN prescription
	ON consultation.date_timestamp = prescription.date_timestamp
		AND consultation.VAT_doctor = prescription.VAT_doctor
	LEFT JOIN procedure_in_consultation
	ON consultation.date_timestamp = procedure_in_consultation.date_timestamp
		AND consultation.VAT_doctor = procedure_in_consultation.VAT_doctor
GROUP BY dim_client.VAT, appointment.date_timestamp;

SELECT * 
FROM facts_consults;
