CREATE
OR REPLACE DATABASE MYTUBE;

CREATE
OR REPLACE TABLE USERS (
    USERNAME VARCHAR(15) PRIMARY KEY,
    PASSWORD VARCHAR(72) NOT NULL,
    EMAIL VARCHAR(30) NOT NULL,
    CREATION_DATE TIMESTAMP
);

USE MYTUBE;

INSERT INTO
    usuario (
        alias,
        password,
        nombre,
        apellidos,
        fecha_nacimiento
    )
VALUES
    (
        'vpop930',
        '$2y$10$1HzjHsvz3FsFXcAm.fX/s.g93FjZNCLhQ.s2dFw1i8Zk8OrPq.d6m',
        'Vlad-George',
        'Popescu',
        STR_TO_DATE('2000-08-20', '%Y-%m-%d')
    );

INSERT INTO
    usuario (
        alias,
        password,
        nombre,
        apellidos,
        fecha_nacimiento
    )
VALUES
    (
        'jmp',
        '$2y$10$GQmK4kDvnTPd/L3ZkFTQMOW7dbUdabKoXgkMni2G3tn5xGv6WXjOK',
        'Jesús',
        'María Prieto',
        STR_TO_DATE('1900-01-01', '%Y-%m-%d')
    );