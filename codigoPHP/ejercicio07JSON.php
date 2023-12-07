<?php
//Incluimos el head
require_once("./head.php");
?>

<title>Ejercicio 07 PHP JSON</title>

<?php
//Incluimos el header
require_once("./header.php");
?>

<h2>Ejercicio 07 JSON</h2>
<p>PDO USANDO LA IMPORTACION JSON</p>

<?php
/**
 * @author Alvaro Cordero
 * @version 1.0
 * @since 16/11/2023
 * 
 * @Annotation Exportando archivos en formato .json
 */


// Incluyo la configuración de conexión a la BD
require_once '../conf/confDBPDO.php';


//Abro un bloque try catch para tener un mayor control de los errores
try {
    // CONEXION CON LA BD
    /**
     * Establecemos la conexión por medio de PDO
     * DSN -> IP del servidor y Nombre de la base de datos
     * USER -> Usuario con el que se conecta a la base de datos
     * PASSWORD -> Contraseña del usuario
     * */
    $miDB = new PDO(DSN, USER, PASSWORD);

    /**
     * Modificamos los errores y añadimos los siguientes atributos de PDO
     */
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Indicamos la ruta del archivo y la guardamos en una variable
    $rutaArchivoJSON = '../tmp/departamentos.json';


    // Leemos el contenido del archivo JSON
    $contenidoArchivoJSON = file_get_contents($rutaArchivoJSON);

    // Decodificamos el JSON a un array asociativo
    $aContenidoDecodificadoArchivoJSON = json_decode($contenidoArchivoJSON, true);

    // Verificamos si la decodificación fue exitosa
    if ($aContenidoDecodificadoArchivoJSON === null && json_last_error() !== JSON_ERROR_NONE) {
        // En caso negativo "matamos" la ejecución del script
        die('Error al decodificar el archivo JSON.');
    }

    // CONSULTAS Y TRANSACCION
    // Deshabilitamos el modo autocommit
    $miDB->beginTransaction();

    /**
     * Realizamos la consulta SQL en una variable heredoc para que sea mas legible
     * En este caso realizamos un insert porque queremos importar los datos de un fichero json a nuestra base de datos
     */
    $consultaInsercion = <<<SQL
    INSERT INTO T02_Departamento
        (T02_CodDepartamento, T02_FechaCreacionDepartamento, T02_DescDepartamento,  T02_VolumenNegocio, T02_FechaBajaDepartamento)
    VALUES
        (:CodDepartamento, :FechaCreacionDepartamento, :DescDepartamento,  :VolumenNegocio, :FechaBajaDepartamento);
    SQL;


    // Preparamos las consultas
    $resultadoconsultaInsercion = $miDB->prepare($consultaInsercion);

    /**
     * Recorremos mediante un foreach el archivo json
     * Y asignamos los valores a cada Departamento
     */
    foreach ($aContenidoDecodificadoArchivoJSON as $aDepartamento) {
        // Recorremos los registros que vamos a insertar en la tabla
        $codDepartamento = $aDepartamento['codDepartamento'];
        $fechaCreacionDepartamento = $aDepartamento['fechaCreacionDepartamento'];
        $descDepartamento = $aDepartamento['descDepartamento'];
        $volumenNegocio = $aDepartamento['volumenNegocio'];
        $fechaBajaDepartamento = $aDepartamento['fechaBajaDepartamento'];

        // Si la fecha de baja está vacía asignamos el valor 'NULL'
        if (empty($fechaBajaDepartamento)) {
            $fechaBajaDepartamento = NULL;
        }

        /**
         * Ininicializamos el array aRegistros con los valores de que vamos a introducir en la consulta
         * En este caso las claves tienen que coincidir con los campos de la tabla de la base de datros
         */
        $aRegistros = [
            ':CodDepartamento' => $codDepartamento,
            ':FechaCreacionDepartamento' => $fechaCreacionDepartamento,
            ':DescDepartamento' => $descDepartamento,
            ':VolumenNegocio' => $volumenNegocio,
            ':FechaBajaDepartamento' => $fechaBajaDepartamento
        ];

        $resultadoconsultaInsercion->execute($aRegistros);
    }

    //Si la entrada es OK 

    // Confirma los cambios y los consolida
    $miDB->commit();
    echo ("<div>Los datos se han insertado correctamente en la tabla Departamento.</div>");

    // Escribimos la consulta a preparar
    $consulta = "SELECT * FROM T02_Departamento";

    //Preparamos la consulta
    $resultadoConsultaPreparada = $miDB->prepare($consulta);

    //Ejecutamos la consulta
    $resultadoConsultaPreparada->execute();

    // Creamos una tabla en la que mostraremos la tabla de la BD
    echo "<table class='table table-bordered' style='width: 50%;'><thead><tr><th>Codigo</th><th>FechaCreacion</th><th>Descripcion</th><th>VolumenNegocio</th><th>FechaBaja</th></tr></thead><tbody>";

    /* Aqui recorremos todos los valores de la tabla, columna por columna, usando el metodo fetchObject, 
          * el cual nos indica que los resultados deben ser devueltos como un array asociativo, donde los nombres de las columnas de 
          * la tabla se utilizan como claves (keys) en el array.
          */
    while ($oDepartartamento = $resultadoConsultaPreparada->fetchObject()) {
        echo ("<tr>");
        echo ("<td>" . $oDepartartamento->T02_CodDepartamento . "</td>");
        echo ("<td>" . $oDepartartamento->T02_FechaCreacionDepartamento . "</td>");
        echo ("<td>" . $oDepartartamento->T02_DescDepartamento . "</td>");
        echo ("<td>" . $oDepartartamento->T02_VolumenNegocio . "</td>");
        echo ("<td>" . $oDepartartamento->T02_FechaBajaDepartamento . "</td>");
        echo ("</tr>");
    }

    echo ("</tbody>");

    /* Ahora usamos la función 'rowCount()' que nos devuelve el número de filas afectadas por la consulta y 
        * almacenamos el valor en la variable '$numeroDeRegistros'
        */
    $numeroDeRegistrosConsultaPreparada = $resultadoConsultaPreparada->rowCount();

    // Y mostramos el número de registros
    echo ("<tfoot ><tr>; color:white;'><td colspan='5'>Número de registros en la tabla Departamento: " . $numeroDeRegistrosConsultaPreparada . '</td></tr></tfoot>');
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