DROP TABLE IF EXISTS procedure_charting;
DROP TABLE IF EXISTS teeth;
DROP TABLE IF EXISTS procedure_in_radiology;
DROP TABLE IF EXISTS procedure_in_consultation;
DROP TABLE IF EXISTS procedure_;
DROP TABLE IF EXISTS prescription;
DROP TABLE IF EXISTS medication;
DROP TABLE IF EXISTS consultation_diagnostic;
DROP TABLE IF EXISTS diagnostic_code_relation;
DROP TABLE IF EXISTS diagnostic_code;
DROP TABLE IF EXISTS consultation_assistant;
DROP TABLE IF EXISTS consultation;
DROP TABLE IF EXISTS appointment;
DROP TABLE IF EXISTS supervision_report;
DROP TABLE IF EXISTS trainee_doctor;
DROP TABLE IF EXISTS permanent_doctor;
DROP TABLE IF EXISTS phone_number_client;
DROP TABLE IF EXISTS client;
DROP TABLE IF EXISTS nurse;
DROP TABLE IF EXISTS doctor;
DROP TABLE IF EXISTS receptionist;
DROP TABLE IF EXISTS phone_number_employee;
DROP TABLE IF EXISTS employee;

CREATE TABLE employee
(
    VAT CHAR(10),
    name VARCHAR(64) NOT NULL,
    birth_date DATE NOT NULL,
    street VARCHAR(32) NOT NULL,
    city VARCHAR(32) NOT NULL,
    zip CHAR(8) NOT NULL,
    IBAN CHAR(25) NOT NULL,
    salary NUMERIC(8, 2) NOT NULL,
    PRIMARY KEY(VAT),
    UNIQUE(IBAN),
    CHECK(salary>=0)
);

CREATE TABLE phone_number_employee
(
    VAT CHAR(10) NOT NULL,
    phone CHAR(9),
    PRIMARY KEY(phone),
    FOREIGN KEY(VAT) REFERENCES employee(VAT) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE receptionist
(
    VAT CHAR(10) NOT NULL,
    FOREIGN KEY(VAT) REFERENCES employee(VAT) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(VAT)
);

CREATE TABLE doctor
(
    VAT CHAR(10) NOT NULL,
    specialization VARCHAR(32) NOT NULL,
    biography VARCHAR(512) NOT NULL,
    email VARCHAR(64) NOT NULL,
    FOREIGN KEY(VAT) REFERENCES employee(VAT) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(VAT),
    UNIQUE(email)
);

CREATE TABLE nurse
(
    VAT CHAR(10) NOT NULL,
    FOREIGN KEY(VAT) REFERENCES employee(VAT) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(VAT)
);

CREATE TABLE client
(
    VAT CHAR(10),
    name VARCHAR(64) NOT NULL,
    birth_date DATE NOT NULL,
    street VARCHAR(32) NOT NULL,
    city VARCHAR(32) NOT NULL,
    zip CHAR(8) NOT NULL,
    gender VARCHAR(6) NOT NULL,
    age INTEGER(3) NOT NULL,
    PRIMARY KEY(VAT),
    CHECK(age BETWEEN 0 AND 150)
);

CREATE TABLE phone_number_client
(
    VAT CHAR(10) NOT NULL,
    phone CHAR(9),
    PRIMARY KEY(phone),
    FOREIGN KEY(VAT) REFERENCES client(VAT) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE permanent_doctor
(
    VAT CHAR(10) NOT NULL,
    years INTEGER(3) NOT NULL,
    FOREIGN KEY(VAT) REFERENCES doctor(VAT) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(VAT),
    CHECK(years BETWEEN 0 AND 100)
);

CREATE TABLE trainee_doctor
(
    VAT CHAR(10) NOT NULL,
    supervisor CHAR(10) NOT NULL,
    FOREIGN KEY(VAT) REFERENCES doctor(VAT) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(supervisor) REFERENCES permanent_doctor(VAT) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(VAT)
);

CREATE TABLE supervision_report
(
    VAT CHAR(10) NOT NULL,
    date_timestamp DATETIME NOT NULL,
    description VARCHAR(512) NOT NULL,
    evaluation INTEGER(1) NOT NULL,
    PRIMARY KEY(date_timestamp),
    FOREIGN KEY(VAT) REFERENCES trainee_doctor(VAT) ON DELETE CASCADE ON UPDATE CASCADE,
    CHECK(evaluation BETWEEN 1 AND 5)
);

CREATE TABLE appointment
(
    VAT_doctor CHAR(10),
    date_timestamp DATETIME,
    description VARCHAR(255) NOT NULL,
    VAT_client CHAR(10) NOT NULL,
    PRIMARY KEY(VAT_doctor, date_timestamp),
    FOREIGN KEY(VAT_doctor) REFERENCES doctor(VAT) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(VAT_client) REFERENCES client(VAT) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE consultation
(
    VAT_doctor CHAR(10),
    date_timestamp DATETIME NOT NULL,
    SOAP_S VARCHAR(512),
    SOAP_O VARCHAR(512),
    SOAP_A VARCHAR(512),
    SOAP_P VARCHAR(512),
    FOREIGN KEY(VAT_doctor, date_timestamp) REFERENCES appointment(VAT_doctor, date_timestamp) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(VAT_doctor, date_timestamp)
);

CREATE TABLE consultation_assistant
(
    VAT_doctor CHAR(10),
    date_timestamp DATETIME NOT NULL,
    VAT_nurse CHAR(10) NOT NULL,
    FOREIGN KEY(VAT_doctor, date_timestamp) REFERENCES consultation(VAT_doctor, date_timestamp) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(VAT_nurse) REFERENCES nurse(VAT) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(VAT_doctor, date_timestamp, VAT_nurse)
);

CREATE TABLE diagnostic_code
(
    ID VARCHAR(7),
    description VARCHAR(255) NOT NULL,
    PRIMARY KEY(ID)
);

CREATE TABLE diagnostic_code_relation
(
    ID1 VARCHAR(7) NOT NULL,
    ID2 VARCHAR(7) NOT NULL,
    type VARCHAR(64) NOT NULL,
    FOREIGN KEY(ID1) REFERENCES diagnostic_code(ID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(ID2) REFERENCES diagnostic_code(ID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE consultation_diagnostic
(
    VAT_doctor CHAR(10),
    date_timestamp DATETIME NOT NULL,
    ID VARCHAR(7) NOT NULL,
    FOREIGN KEY(VAT_doctor, date_timestamp) REFERENCES consultation(VAT_doctor, date_timestamp) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(ID) REFERENCES diagnostic_code(ID) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(VAT_doctor, date_timestamp, ID)
);

CREATE TABLE medication
(
    name VARCHAR(64),
    lab VARCHAR(64),
    PRIMARY KEY(name, lab)
);

CREATE TABLE prescription
(
    name VARCHAR(64) NOT NULL,
    lab VARCHAR(64) NOT NULL,
    VAT_doctor CHAR(10),
    date_timestamp DATETIME NOT NULL,
    ID VARCHAR(7) NOT NULL,
    dosage VARCHAR(32) NOT NULL,
    description VARCHAR(64) NOT NULL,
    FOREIGN KEY(name, lab) REFERENCES medication(name, lab) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(VAT_doctor, date_timestamp, ID) REFERENCES consultation_diagnostic(VAT_doctor, date_timestamp, ID) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(name, lab, VAT_doctor, date_timestamp, ID)
);

CREATE TABLE procedure_
(
    name VARCHAR(64),
    type VARCHAR(64) NOT NULL,
    PRIMARY KEY(name)
);

CREATE TABLE procedure_in_consultation
(
    name VARCHAR(64) NOT NULL,
    VAT_doctor CHAR(10),
    date_timestamp DATETIME NOT NULL,
    description VARCHAR(255) NOT NULL,
    FOREIGN KEY(name) REFERENCES procedure_(name) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(VAT_doctor, date_timestamp) REFERENCES consultation(VAT_doctor, date_timestamp) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(name, VAT_doctor, date_timestamp)
);

CREATE TABLE procedure_in_radiology
(
    name VARCHAR(64) NOT NULL,
    file VARCHAR
    (255),
    VAT_doctor CHAR
    (10),
    date_timestamp DATETIME NOT NULL,
    PRIMARY KEY
    (file),
    FOREIGN KEY
    (name) REFERENCES procedure_in_consultation
    (name) ON
    DELETE CASCADE ON
    UPDATE CASCADE,
    FOREIGN KEY(VAT_doctor, date_timestamp) REFERENCES procedure_in_consultation(VAT_doctor, date_timestamp)
    ON
    DELETE CASCADE ON
    UPDATE CASCADE,
    UNIQUE(name, VAT_doctor, date_timestamp)
);

    CREATE TABLE teeth
    (
        quadrant VARCHAR(11),
        number INTEGER(1),
        name VARCHAR(32) NOT NULL,
        PRIMARY KEY(quadrant, number),
        CHECK(number BETWEEN 1 AND 8)
    );

    CREATE TABLE procedure_charting
    (
        name VARCHAR(64) NOT NULL,
        VAT CHAR(10),
        date_timestamp DATETIME NOT NULL,
        quadrant VARCHAR(11) NOT NULL,
        number INTEGER(1) NOT NULL,
        description VARCHAR(255) NOT NULL,
        measure NUMERIC(3,1) NOT NULL,
        FOREIGN KEY(name) REFERENCES procedure_in_consultation(name) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY(VAT, date_timestamp) REFERENCES procedure_in_consultation(VAT_doctor, date_timestamp) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY(quadrant, number) REFERENCES teeth(quadrant, number) ON DELETE CASCADE ON UPDATE CASCADE,
        UNIQUE(name, VAT, date_timestamp, quadrant, number),
        CHECK(measure>=0)
    );

