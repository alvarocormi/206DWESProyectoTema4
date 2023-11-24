<?php

/**
 * @author: Alvaro Cordero Miñambres
 * @since: 23/11/2022
 * @copyright: Copyright (c) 2023, Alvaro Cordero Miñambres
 * Script cragainicial tabla departamento
 */
//Incluyo las variables de la conexion
require_once '../conf/confDB.php';

try {
    //Hago la conexion con la base de datos
    $miDB = new PDO(DSN, USER, PASSWORD);

    // Establezco el atributo para la aparicion de errores con ATTR_ERRMODE y le pongo que cuando haya un error se lance una excepcion con ERRMODE_EXCEPTION
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Consulta para eliminar las tablas
    $consulta = <<<CONSULTA
                USE dbs12302430;

                INSERT INTO T02_Departamento (T02_CodDepartamento, T02_FechaCreacionDepartamento, T02_DescDepartamento, T02_VolumenNegocio, T02_FechaBajaDepartamento)
                VALUES ('001', CURDATE(), 'Departamento de Ventas', 500000.0, NULL);

                INSERT INTO T02_Departamento (T02_CodDepartamento, T02_FechaCreacionDepartamento, T02_DescDepartamento, T02_VolumenNegocio, T02_FechaBajaDepartamento)
                VALUES ('002',CURDATE(), 'Departamento de Marketing', 300000.0, NULL);

                INSERT INTO T02_Departamento (T02_CodDepartamento, T02_FechaCreacionDepartamento, T02_DescDepartamento, T02_VolumenNegocio, T02_FechaBajaDepartamento)
                VALUES ('003', CURDATE(), 'Departamento de Recursos Humanos', 250000.0, NULL);

                INSERT INTO T02_Departamento (T02_CodDepartamento, T02_FechaCreacionDepartamento, T02_DescDepartamento, T02_VolumenNegocio, T02_FechaBajaDepartamento)
                VALUES ('004', CURDATE(), 'Departamento de Desarrollo', 400000.0, NULL);
    CONSULTA;

    $miDB->exec($consulta); //Ejecuto la consulta

    echo 'Tablas vargadas';
} catch (PDOException $excepcion) { //Codigo que se ejecuta si hay algun error
    $errorExcepcion = $excepcion->getCode(); //Obtengo el codigo del error y lo almaceno en la variable errorException
    $mensajeException = $excepcion->getMessage(); //Obtengo el mensaje del error y lo almaceno en la variable mensajeException
    echo "<span style='color: red'>Codigo del error: </span>" . $errorExcepcion; //Muestro el codigo del error
    echo "<span style='color: red'>Mensaje del error: </span>" . $mensajeException; //Muestro el mensaje del error
} finally {
    //Cierro la conexion
    unset($miDB);
}
