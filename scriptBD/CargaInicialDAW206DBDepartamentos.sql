/*
    Autor: Alvaro Cordero Miñambres.
    Utilidad: Este programa consiste en cargar datos en la tabla Departamento.
    Fecha-última-revisión: 01-11-2023.
*/
START TRANSACTION;
-- Usamos la base de datos de Departamento
USE DB206DWESProyectoTema4;
-- Inserción de datos en la tabla Departamento.
INSERT INTO Departamento VALUES
("INF","Departamento de Informatica",null,2200.5),
("FOL","Departamento de FOL",null,1200.5),
("HIS","Departamento de Historia",null,200.15),
("MAT","Departamento de Matemáticas",null,1620.7);

COMMIT;