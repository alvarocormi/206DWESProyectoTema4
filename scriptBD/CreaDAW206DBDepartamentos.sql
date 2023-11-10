/*
    Autor: Alvaro Cordero Miñambres
    Fecha-última-revisión: 31-10-2023.
*/
START TRANSACTION;

-- Creación de la base de datos.
CREATE DATABASE IF NOT EXISTS DB206DWESProyectoTema4;
USE DB206DWESProyectoTema4;

-- Creacion de la tabla Departamento
CREATE TABLE IF NOT EXISTS Departamento(
   CodDepartamento VARCHAR(3) PRIMARY KEY,
   DescDepartamento VARCHAR(255),
   FechaBaja DATETIME,
   VolumenNegocio FLOAT
)engine=Innodb;

-- Creación del usuario.
CREATE USER 'user206DWESProyectoTema4'@'%' IDENTIFIED BY 'P@ssw0rd';
-- Dar permisos al usuario 'usuarioDAW204DBDepartamentos'.
GRANT ALL ON DB206DWESProyectoTema4.* to 'user206DWESProyectoTema4'@'%';

COMMIT;