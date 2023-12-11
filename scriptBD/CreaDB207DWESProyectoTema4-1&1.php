<?php
/**
 * @author: Alvaro Cordero Miñambres
 * @since: 23/11/2022
 * @copyright: Copyright (c) 2023, Alvaro Cordero Miñambres
 * Script creadcion de la base de datos
 */

define('DSN', 'mysql:host=db5014806762.hosting-data.io;dbname=dbs12302430');//Direccion IP del host y nombre de la base de datos
define('USER', 'dbu1636093');//Nombre del usuario de la base de datos
define('PASSWORD', 'daw2_Sauces');//Contraseña del usuario de la base de datos

try {
    //Hago la conexion con la base de datos
    $miDB = new PDO(DSN, USER, PASSWORD);

    //Consulta para eliminar las tablas
    $consulta = <<<CONSULTA
        CREATE TABLE IF NOT EXISTS dbs12302430.T02_Departamento(
            T02_CodDepartamento VARCHAR(3) PRIMARY KEY,
            T02_DescDepartamento VARCHAR(255),
            T02_FechaCreacionDepartamento DATETIME,
            T02_VolumenDeNegocio FLOAT,
            T02_FechaBajaDepartamento DATETIME
        )engine=Innodb;
     
    CONSULTA;

    $miDB->exec($consulta); //Ejecuto la consulta

    echo 'Tablas creadas con exito';
} catch (PDOException $excepcion) {//Codigo que se ejecuta si hay algun error
    $errorExcepcion = $excepcion->getCode(); //Obtengo el codigo del error y lo almaceno en la variable errorException
    $mensajeException = $excepcion->getMessage(); //Obtengo el mensaje del error y lo almaceno en la variable mensajeException
    echo "<span style='color: red'>Codigo del error: </span>" . $errorExcepcion; //Muestro el codigo del error
    echo "<span style='color: red'>Mensaje del error: </span>" . $mensajeException; //Muestro el mensaje del error
} finally {
    //Cierro la conexion
    unset($miDB);
}
?>