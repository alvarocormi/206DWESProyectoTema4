<?php

/**
 * @author: Alvaro Cordero Miñambres
 * @since: 23/11/2022
 * @copyright: Copyright (c) 2023, Alvaro Cordero Miñambres
 * Script cragainicial tabla departamento
 */

define('DSN', 'mysql:host=db5014806762.hosting-data.io;dbname=dbs12302430');//Direccion IP del host y nombre de la base de datos
define('USER', 'dbu1636093');//Nombre del usuario de la base de datos
define('PASSWORD', 'daw2_Sauces');//Contraseña del usuario de la base de datos

try {
    //Hago la conexion con la base de datos
    $miDB = new PDO(DSN, USER, PASSWORD);

    //Consulta para eliminar las tablas
    $consulta = <<<CONSULTA

        INSERT INTO
        dbs12302430.T02_Departamento (
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
    CONSULTA;

    $consultaPrepada = $miDB->prepare($consulta); //Ejecuto la consulta
    $consultaPrepada->execute(); //Ejecuto la consulta

    echo 'Tablas cargadas con exito';
} catch (PDOException $excepcion) { //Codigo que se ejecuta si hay algun error
    $errorExcepcion = $excepcion->getCode(); //Obtengo el codigo del error y lo almaceno en la variable errorException
    $mensajeException = $excepcion->getMessage(); //Obtengo el mensaje del error y lo almaceno en la variable mensajeException
    echo "<span style='color: red'>Codigo del error: </span>" . $errorExcepcion; //Muestro el codigo del error
    echo "<span style='color: red'>Mensaje del error: </span>" . $mensajeException; //Muestro el mensaje del error
} finally {
    //Cierro la conexion
    unset($miDB);
}
