/*
    Autor: Alvaro Cordero Miñambres
    Fecha-última-revisión: 31-10-2023.
*/
START TRANSACTION;

-- Creación de la base de datos.
CREATE DATABASE IF NOT EXISTS DB206DWESProyectoTema4;
USE DB206DWESProyectoTema4;

-- Creacion de la tabla Departamento
CREATE TABLE IF NOT EXISTS T02_Departamento(
   T02_CodDepartamento VARCHAR(3) PRIMARY KEY,
   T02_FechaCreacionDepartamento DATETIME NOT NULL,
   T02_DescDepartamento VARCHAR(255) NOT NULL,
   T02_VolumenNegocio FLOAT NOT NULL,
   T02_FechaBajaDepartamento DATETIME NULL
)engine=Innodb;

-- Creación del usuario.
CREATE USER 'user206DWESProyectoTema4'@'%' IDENTIFIED BY 'P@ssw0rd';
-- Dar permisos al usuario 'usuarioDAW204DBDepartamentos'.
GRANT ALL ON DB206DWESProyectoTema4.* to 'user206DWESProyectoTema4'@'%';

COMMIT;