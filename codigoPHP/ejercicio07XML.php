<?php
//Incluimos el head
require_once("./head.php");
?>

<title>Ejercicio 07 XML JSON</title>

<?php
//Incluimos el header
require_once("./header.php");
?>

<h2>Ejercicio 07 XML</h2>
<p>PDO USANDO LA IMPORTACION XML</p>

<?php

/**
 * @author Alvaro Cordero
 * @version 1.0
 * @since 20/11/2023
 */

// Incluyo la configuración de conexión a la BD
require_once '../conf/confDB.php';



//Abro un bloque try catch para tener un mayor control de los errores
try {
    //CONEXION CON LA BD
    /**
     * Establecemos la conexión por medio de PDO
     * DSN -> IP del servidor y Nombre de la base de datos
     * USER -> Usuario con el que se conecta a la base de datos
     * PASSWORD -> Contraseña del usuario
     * */
    $miDB = new PDO(DSN, USER, PASSWORD);

    /**
     * Realizamos la consulta de Insercion
     * Metemos el insert en una variable HEREDOC
     */
    $consultaInsercion = <<<SQL
    INSERT T02_Departamento
        (T02_CodDepartamento,  T02_FechaCreacionDepartamento ,T02_DescDepartamento, T02_VolumenNegocio, T02_FechaBajaDepartamento) 
    VALUES 
        (:CodDepartamento, :FechaCreacionDepartamento, :DescDepartamento,  :VolumenDeNegocio, :FechaBajaDepartamento);
    SQL;

    //Preparamos la sonculta de inseracion
    $resultadoconsultaInsercion = $miDB->prepare($consultaInsercion);

    // CONSULTAS Y TRANSACCION
    // Deshabilitamos el modo autocommit
    $miDB->beginTransaction();

    //Instanciamos un objeto DOMDocument
    $archivoXML = new DOMDocument("1.0", "utf-8");

    //Le asigno la salida con formato
    $archivoXML->formatOutput = true;

    // Cargamos el archivo 'xml' indicandole la ruta
    $archivoXML->load('../tmp/departamentos.xml');

    //Cogemos el nodo departamento del documento XML
    $departamento = $archivoXML->getElementsByTagName('Departamento');

    // Recorremos mediante un foreach el nodo principal Departamento
    foreach ($departamento as $valor) {
        /**
         * Cogemos los valores de cada nodo accediendo a ellos getElementsByTagName()
         * y cogemos el valor del item mediante el nodeValue
         */
        $codDepartamento = $valor->getElementsByTagName("CodDepartamento")->item(0)->nodeValue;
        $fechaCreacionDepartamento = $valor->getElementsByTagName("FechaCreacionDepartamento")->item(0)->nodeValue;
        $descDepartamento = $valor->getElementsByTagName("DescDepartamento")->item(0)->nodeValue;
        $volumenNegocio = $valor->getElementsByTagName("VolumenNegocio")->item(0)->nodeValue;
        $fechaBajaDepartamento = $valor->getElementsByTagName("FechaBajaDepartamento")->item(0)->nodeValue;

        /**
         * Creamos un array llamado aRegistros que almacena los valores del documento XML 
         */
        $aRegistros = [
            ':CodDepartamento' => $codDepartamento,
            ':FechaCreacionDepartamento' => $fechaCreacionDepartamento,
            ':DescDepartamento' => $descDepartamento,
            ':VolumenDeNegocio' => $volumenNegocio,
            ':FechaBajaDepartamento' => $fechaBajaDepartamento
        ];

        //Ejecutamos la consulta
        $resultadoconsultaInsercion->execute($aRegistros);
    }



    // Confirma los cambios y los consolida
    $miDB->commit();
    echo ("<div class='respuestaCorrecta'>Los datos se han insertado correctamente en la tabla Departamento.</div>");

    // Preparamos y ejecutamos la consulta SQL
    $consulta = "SELECT * FROM T02_Departamento";

    //Preparamos la consulta
    $resultadoConsultaPreparada = $miDB->prepare($consulta);

    //Ejecutamos la consulta
    $resultadoConsultaPreparada->execute();

    // Creamos una tabla en la que mostraremos la tabla de la BD
    echo ("<div class='list-group text-center'>");
    echo ("<table>
            <thead>
            <tr>
                <th>Codigo de Departamento</th>
                <th>Fecha de Creacion</th>
                <th>Descripcion de Departamento</th>
                <th>Volumen de Negocio</th>
                <th>Fecha de Baja</th>
            </tr>
            </thead>");
    echo ("<tbody>");

    /* Aqui recorremos todos los valores de la tabla, columna por columna, usando el parametro 'PDO::FETCH_ASSOC' , 
    * el cual nos indica que los resultados deben ser devueltos como un array asociativo, donde los nombres de las columnas de 
    * la tabla se utilizan como claves (keys) en el array.
    */
    while ($oDepartamento = $resultadoConsultaPreparada->fetchObject()) {
        echo ("<tr>");
        echo ("<td>" . $oDepartamento->T02_CodDepartamento . "</td>");
        echo ("<td>" . $oDepartamento->T02_FechaCreacionDepartamento . "</td>");
        echo ("<td>" . $oDepartamento->T02_DescDepartamento . "</td>");
        echo ("<td>" . $oDepartamento->T02_VolumenNegocio . "</td>");
        echo ("<td>" . $oDepartamento->T02_FechaBajaDepartamento . "</td>");
        echo ("</tr>");
    }

    echo ("</tbody>");

    /* Ahora usamos la función 'rowCount()' que nos devuelve el número de filas afectadas por la consulta y 
    * almacenamos el valor en la variable '$numeroDeRegistros'
    */
    $numeroDeRegistrosConsultaPreparada = $resultadoConsultaPreparada->rowCount();

    // Y mostramos el número de registros
    echo ("<tfoot ><tr style='background-color: #666; color:white;'><td colspan='5'>Número de registros en la tabla Departamento: " . $numeroDeRegistrosConsultaPreparada . '</td></tr></tfoot>');
    echo ("</table>");
    echo ("</div>");

    //Controlamos las excepciones mediante la clase PDOException
} catch (PDOException $miExcepcionPDO) {
    /**
     * Revierte o deshace los cambios
     * Esto solo se usara si estamos usando consultas preparadas
     */
    $miDB->rollback();

    //Almacenamos el código del error de la excepción en la variable '$errorExcepcion'
    $errorExcepcion = $miExcepcionPDO->getCode();

    // Almacenamos el mensaje de la excepción en la variable '$mensajeExcepcion'
    $mensajeExcepcion = $miExcepcionPDO->getMessage();

    // Mostramos el mensaje de la excepción
    echo "<span style='color: red'>Error: </span>" . $mensajeExcepcion . "<br>";

    // Mostramos el código de la excepción
    echo "<span style='color: red'>Código del error: </span>" . $errorExcepcion;

    //En culaquier cosa cerramos la sesion
} finally {
    // El metodo unset sirve para cerrar la sesion con la base de datos
    unset($miDB);
}
?>
</div>
</main>
</body>
<?php
//Importamos el footer
require_once("./footer.php")
?>

</html>