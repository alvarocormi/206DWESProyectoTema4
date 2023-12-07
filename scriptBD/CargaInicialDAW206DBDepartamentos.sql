/*
    Autor: Alvaro Cordero Miñambres.
    Utilidad: Este programa consiste en cargar datos en la tabla Departamento.
    Fecha-última-revisión: 01-11-2023.
*/
START TRANSACTION;
-- Usamos la base de datos de Departamento
USE DB206DWESProyectoTema4;
-- Inserción de datos en la tabla Departamento.
INSERT INTO
  T02_Departamento (
    T02_CodDepartamento,
    T02_DescDepartamento,
    T02_FechaCreacionDepartamento,
    T02_VolumenDeNegocio,
    T02_FechaBajaDepartamento
  )
VALUES
  (
    'AAA',
    'Departamento de Ventas',
    NOW(),
    100000.50,
    NULL
  ),
  (
    'AAB',
    'Departamento de Marketing',
    NOW(),
    50089.50,
    NULL
  ),
  (
    'AAC',
    'Departamento de Finanzas',
    NOW(),
    600.50,
    '2023-11-13 13:06:00'
  );
COMMIT;