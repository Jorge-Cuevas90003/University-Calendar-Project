-- Database: Proyecto1

-- DROP DATABASE IF EXISTS "Proyecto1";




CREATE DATABASE "Proyecto1"
    WITH
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'Spanish_Spain.1252'
    LC_CTYPE = 'Spanish_Spain.1252'
    LOCALE_PROVIDER = 'libc'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1
    IS_TEMPLATE = False;

-- Crear la tabla T_eventos
CREATE TABLE T_eventos (
    id_tipo SERIAL PRIMARY KEY,
    nombre VARCHAR(55) NOT NULL,
    frecuencia VARCHAR(55) NOT NULL -- Define el tipo de frecuencia (e.g., 'diaria', 'semanal', 'mensual')
);

-- Crear la tabla Contactos
CREATE TABLE Contactos (
    email VARCHAR(55) PRIMARY KEY,
    nombre VARCHAR(55) NOT NULL,
    direccion VARCHAR(55),
    telefono VARCHAR(15),
    fecha_nacimiento DATE
);

-- Crear la tabla Eventos
CREATE TABLE Eventos (
    id_evento SERIAL PRIMARY KEY,
    id_tipo INT NOT NULL,
    titulo_evento VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha DATE NOT NULL,
    hora TIME,
    email VARCHAR(55) NOT NULL,
    FOREIGN KEY(id_tipo) REFERENCES T_eventos(id_tipo),
    FOREIGN KEY(email) REFERENCES Contactos(email)
);

-- Crear la función para el trigger
CREATE OR REPLACE FUNCTION agregar_evento_cumpleanos()
RETURNS TRIGGER AS $$
BEGIN
    -- Verificar si se proporcionó una fecha de nacimiento
    IF NEW.fecha_nacimiento IS NOT NULL THEN
        -- Insertar el evento de cumpleaños para el cumpleaños actual
        INSERT INTO Eventos (id_tipo, titulo_evento, descripcion, fecha, hora, email)
        VALUES (
            (SELECT id_tipo FROM T_eventos WHERE nombre = 'Cumpleaños'),
            'Cumpleaños de ' || NEW.nombre,
            'Cumpleaños',
            DATE_TRUNC('year', CURRENT_DATE) + (NEW.fecha_nacimiento - DATE_TRUNC('year', NEW.fecha_nacimiento)),
            '00:00:00', -- Hora a las 12:00 AM
            NEW.email
        );
        -- Insertar el evento de cumpleaños para el próximo año
        INSERT INTO Eventos (id_tipo, titulo_evento, descripcion, fecha, hora, email)
        VALUES (
            (SELECT id_tipo FROM T_eventos WHERE nombre = 'Cumpleaños'),
            'Cumpleaños de ' || NEW.nombre,
            'Cumpleaños',
            DATE_TRUNC('year', CURRENT_DATE) + (NEW.fecha_nacimiento - DATE_TRUNC('year', NEW.fecha_nacimiento)) + INTERVAL '1 year',
            '00:00:00', -- Hora a las 12:00 AM
            NEW.email
        );
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Crear el trigger que se activará después de insertar en la tabla Contactos
CREATE TRIGGER after_insert_contacto
AFTER INSERT ON Contactos
FOR EACH ROW
EXECUTE FUNCTION agregar_evento_cumpleanos();
