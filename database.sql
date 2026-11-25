-- =============================================================
-- database.sql -> crea la base de datos y la tabla de la app
-- Se ejecuta UNA sola vez en el servidor con:
--   sudo mariadb < database.sql
-- (El usuario de la base con su contraseña se crea aparte, a mano,
--  para que la contraseña NO quede en GitHub)
-- =============================================================

CREATE DATABASE IF NOT EXISTS centro_computos
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE centro_computos;

-- Tabla "equipos": cada fila es un equipo del centro de cómputos
CREATE TABLE IF NOT EXISTS equipos (
  id             INT AUTO_INCREMENT PRIMARY KEY,   -- número único, se pone solo
  nombre         VARCHAR(100) NOT NULL,            -- ej: PC-LAB-01
  tipo           VARCHAR(50)  NOT NULL,            -- PC, Notebook, Servidor...
  marca          VARCHAR(50)  NOT NULL,            -- ej: Dell, HP, Lenovo
  ubicacion      VARCHAR(100) NOT NULL,            -- ej: Laboratorio 1
  estado         VARCHAR(30)  NOT NULL,            -- Operativo, En reparación...
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- fecha en que se cargó
);

-- Un par de equipos de ejemplo para que la tabla no arranque vacía
INSERT INTO equipos (nombre, tipo, marca, ubicacion, estado) VALUES
  ('SRV-01', 'Servidor', 'Dell', 'Sala de servidores', 'Operativo'),
  ('PC-LAB-01', 'PC de escritorio', 'HP', 'Laboratorio 1', 'En reparación');
