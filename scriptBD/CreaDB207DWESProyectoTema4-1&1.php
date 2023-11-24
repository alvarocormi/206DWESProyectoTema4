<?php
/**
 * @author: Alvaro Cordero Miñambres
 * @since: 23/11/2022
 * @copyright: Copyright (c) 2023, Alvaro Cordero Miñambres
 * Script creadcion de la base de datos
 */
//Incluyo las variables de la conexion
require_once '../conf/confDB.php';

try {
    //Hago la conexion con la base de datos
    $miDB = new PDO(DSN, USER, PASSWORD);

    // Establezco el atributo para la aparicion de errores con ATTR_ERRMODE y le pongo que cuando haya un error se lance una excepcion con ERRMODE_EXCEPTION
    $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Consulta para eliminar las tablas
    $consulta = <<<CONSULTA
                
            USE dbs12302430;


            CREATE TABLE IF NOT EXISTS T02_Departamento(
                T02_CodDepartamento VARCHAR(3) PRIMARY KEY,
                T02_FechaCreacionDepartamento DATETIME NOT NULL,
                T02_DescDepartamento VARCHAR(255) NOT NULL,
                T02_VolumenNegocio FLOAT NOT NULL,
                T02_FechaBajaDepartamento DATETIME NULL
            )engine=Innodb;

            CREATE USER 'dbu1636093'@'%' IDENTIFIED BY 'P@ssw0rd';

            GRANT ALL ON dbs12302430.* to 'dbu1636093'@'%' 

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