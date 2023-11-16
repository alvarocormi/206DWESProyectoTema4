/*
    Autor: Alvaro Cordero Miñambres.
    Utilidad: Este programa consiste en cargar datos en la tabla Departamento.
    Fecha-última-revisión: 01-11-2023.
*/
START TRANSACTION;
-- Usamos la base de datos de Departamento
USE DB206DWESProyectoTema4;
-- Inserción de datos en la tabla Departamento.
INSERT INTO T02_Departamento (T02_CodDepartamento, T02_FechaCreacionDepartamento, T02_DescDepartamento, T02_VolumenNegocio, T02_FechaBajaDepartamento)
VALUES ('001', CURDATE(), 'Departamento de Ventas', 500000.0, NULL);
INSERT INTO T02_Departamento (T02_CodDepartamento, T02_FechaCreacionDepartamento, T02_DescDepartamento, T02_VolumenNegocio, T02_FechaBajaDepartamento)
VALUES ('002',CURDATE(), 'Departamento de Marketing', 300000.0, NULL);
INSERT INTO T02_Departamento (T02_CodDepartamento, T02_FechaCreacionDepartamento, T02_DescDepartamento, T02_VolumenNegocio, T02_FechaBajaDepartamento)
VALUES ('003', CURDATE(), 'Departamento de Recursos Humanos', 250000.0, NULL);
INSERT INTO T02_Departamento (T02_CodDepartamento, T02_FechaCreacionDepartamento, T02_DescDepartamento, T02_VolumenNegocio, T02_FechaBajaDepartamento)
VALUES ('004', CURDATE(), 'Departamento de Desarrollo', 400000.0, NULL);

COMMIT;