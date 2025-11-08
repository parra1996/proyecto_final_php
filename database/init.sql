SELECT 'CREATE DATABASE tienda_php'
WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'tienda');

CREATE EXTENSION IF NOT EXISTS pgcrypto;

DROP TABLE IF EXISTS "productos";
DROP SEQUENCE IF EXISTS productos_id_seq;
DROP TABLE IF EXISTS "user_roles";
DROP TABLE IF EXISTS "usuarios";
DROP SEQUENCE IF EXISTS usuarios_id_seq;
DROP TABLE IF EXISTS "categorias";


CREATE SEQUENCE productos_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;
CREATE TABLE "public"."productos"
(
    "is_deleted" boolean DEFAULT false,
    "precio" double precision DEFAULT '0.0',
    "stock" integer DEFAULT '0',
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    "id" bigint DEFAULT nextval('productos_id_seq'),
    "updated_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    "categoria_id" uuid,
    "categoria_nombre" character varying(255),
    "uuid" uuid,
    "descripcion" character varying(255),
    "imagen" text DEFAULT 'https://via.placeholder.com',
    "marca" character varying(255),
    "modelo" character varying(255),
    CONSTRAINT "productos_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "productos_uuid_key" UNIQUE ("uuid")
) WITH (oids = false);

INSERT INTO "productos" ("is_deleted", "precio", "stock", "created_at",
    "updated_at", "categoria_id", "categoria_nombre", "uuid",
    "descripcion", "imagen", "marca", "modelo")
    VALUES (false, 10.99, 5, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL, NULL, gen_random_uuid(), 
        'Zapatillas deportivas', 'https://via.placeholder.com/150', 'Nike', 'Zapas 2300 Nike'),
        (false, 19.99, 10, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL, NULL, gen_random_uuid(), 
        'Camiseta deportiva', 'https://via.placeholder.com/150', 'Adidas', 'Camiseta ceñida 170N'),
        (false, 15.99, 2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL, NULL, gen_random_uuid(), 
        'Pantalón de entrenamiento', 'https://via.placeholder.com/150', 'Nike', 'Pantalón chandal Nike Plus'),
        (false, 25.99, 8, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL, NULL, gen_random_uuid(), 
        'Sudadera con capucha', 'https://via.placeholder.com/150', 'Nike', 'Sudadera Hotwheels'),
        (false, 12.99, 3, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL, NULL, gen_random_uuid(), 
        'Calcetines deportivos', 'https://via.placeholder.com/150', 'Adidas', 'Calcetines Mickey');

CREATE TABLE "public"."user_roles"
(
"user_id" bigint NOT NULL,
"roles" character varying(255)
) WITH (oids = false);

INSERT INTO "user_roles" ("user_id", "roles")
    VALUES (1, 'USER'),
    (1, 'ADMIN'),
    (2, 'USER'),
    (2, 'USER'),
    (3, 'USER');

CREATE SEQUENCE usuarios_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;

CREATE TABLE "public"."usuarios"
    (
    "is_deleted" boolean DEFAULT false,
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    "id" bigint DEFAULT nextval('usuarios_id_seq') NOT NULL,
    "updated_at" timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    "apellidos" character varying(255) NOT NULL,
    "email" character varying(255) NOT NULL,
    "nombre" character varying(255) NOT NULL,
    "password" character varying(255) NOT NULL,
    "username" character varying(255) NOT NULL,
    CONSTRAINT "usuarios_email_key" UNIQUE ("email"),
    CONSTRAINT "usuarios_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "usuarios_username_key" UNIQUE ("username")
    ) WITH (oids = false);
-- Contraseña: admin Admin1
-- Contraseña: user User1234
-- Contraseña: test test1234
INSERT INTO "usuarios" ("is_deleted", "created_at", "id", "updated_at",
 "apellidos", "email", "nombre", "password", "username")
    VALUES (false, '2023-11-02 11:43:24.724871', 1, '2023-11-02 11:43:24.724871', 'Admin', 'admin@example.com', 
        'Administrador', '$2a$10$vPaqZvZkz6jhb7U7k/V/v.5vprfNdOnh4sxi/qpPRkYTzPmFlI9p2', 'admin1'),
        (false, '2023-11-02 11:43:24.730431', 2, '2023-11-02 11:43:24.730431', 'User', 'user@example.com', 
        'Usuario', '$2a$12$RUq2ScW1Kiizu5K4gKoK4OTz80.DWaruhdyfi2lZCB.KeuXTBh0S.', 'user1'),
        (false, '2023-11-02 11:43:24.733552', 3, '2023-11-02 11:43:24.733552', 'Test', 'test@example.com', 
        'Test', '$2a$10$Pd1yyq2NowcsDf4Cpf/ZXObYFkcycswqHAqBndE1wWJvYwRxlb.Pu', 'test1'),
        (false, '2023-11-02 11:43:24.736674', 4, '2023-11-02 11:43:24.736674', 'Guest', 'guest@example.com', 
        'Invitado', '$2a$12$3Q4.UZbvBMBEvIwwjGEjae/zrIr6S50NusUlBcCNmBd2382eyU0bS', 'guest1');

CREATE TABLE "public"."categorias"
    (
    "is_deleted" boolean DEFAULT false,
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    "updated_at" timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    "id" uuid NOT NULL,
    "nombre" character varying(255) NOT NULL,
    CONSTRAINT "categorias_nombre_key" UNIQUE ("nombre"),
    CONSTRAINT "categorias_pkey" PRIMARY KEY ("id")
    ) WITH (oids = false);

INSERT INTO "categorias" ("is_deleted", "created_at", "updated_at", "id", "nombre")
    VALUES (false, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, gen_random_uuid(), 'DEPORTES'),
        (false, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, gen_random_uuid(), 'COMIDA'),
        (false, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, gen_random_uuid(), 'BEBIDA'),
        (false, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, gen_random_uuid(), 'COMPLEMENTOS'),
        (false, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, gen_random_uuid(), 'OTROS');

ALTER TABLE ONLY "public"."productos"
    ADD CONSTRAINT "fk2fwq10nwymfv7fumctxt9vpgb" FOREIGN KEY (categoria_id) REFERENCES categorias(id);

ALTER TABLE ONLY "public"."user_roles"
    ADD CONSTRAINT "fk2chxp26bnpqjibydrikgq4t9e" FOREIGN KEY (user_id) REFERENCES usuarios(id);
