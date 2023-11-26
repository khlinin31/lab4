CREATE SCHEMA IF NOT EXISTS site;

CREATE TABLE site.user
(
    id       SERIAL PRIMARY KEY,
    login    VARCHAR(64) NOT NULL UNIQUE,
    password VARCHAR(64) NOT NULL
);

CREATE TABLE site.order
(
    id         SERIAL PRIMARY KEY,
    user_id    INTEGER NOT NULL REFERENCES site.user (id),
    name       VARCHAR(64) NOT NULL,
    surname    VARCHAR(64) NOT NULL,
    patronymic VARCHAR(64) NOT NULL,
    address    TEXT    NOT NULL,
    number     VARCHAR(64) NOT NULL,
    email      VARCHAR(64) NOT NULL,
    comment    TEXT
);

CREATE TABLE site.good
(
    id             SERIAL PRIMARY KEY,
    name           VARCHAR(64) NOT NULL UNIQUE,
    localized_name VARCHAR(64)
);

CREATE TABLE site.order_to_good
(
    order_id INTEGER NOT NULL REFERENCES site.order (id),
    good_id  INTEGER NOT NULL REFERENCES site.good (id)
);

CREATE INDEX good_name_index ON site.good(name);
CREATE INDEX user_login_index ON site.user(login);

