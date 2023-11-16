<?php
//Incluimos el head
require_once("./head.php");
?>

<title>Ejercicio 08 PHP JSON</title>

<?php
//Incluimos el header
require_once("./header.php");
?>

<h2>Ejercicio 08 JSON</h2>
<p>PDO USANDO LA EXPORTACION CON JSON</p>

<?php
/**
 * @author Alvaro Cordero
 * @version 1.0
 * @since 15/11/2023
 * 
 * @Annotation Exportando archivos en formato .json
 */


/**Funciones para tener un mayor control sobre nuestros errores
 *
 * La función ini_set('display_errors', 1); es una instrucción de configuración en PHP que se utiliza para activar la visualización de 
   errores en tiempo de ejecución en el navegador web.
 * Mostrará los errores directamente en la página web si ocurren durante la ejecución del script PHP.
 */
ini_set('display_errors', 1);

/**Se utiliza para activar la visualización de errores que ocurren durante 
 * el inicio del script, es decir, durante la fase de arranque (startup) del proceso PHP. */
ini_set('display_startup_errors', 1);

/**
 * Establece el nivel de error que se informará durante la ejecución de un script PHP. 
 * En este caso, E_ALL es una constante que representa todos los tipos de errores posibles en PHP.
 */
error_reporting(E_ALL);

// Incluyo la configuración de conexión a la BD
require_once '../conf/confDB.php';

// Declaro una variable de entrada para mostrar o no la tabla con los valores de la BD
$entradaOK = true;

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

    /**
     * Declaracion de la consulta SQL 
     * En este caso hacemos un select de la tabla Departamanetos
     */
    $sql1 = 'SELECT * FROM T02_Departamento';

    //Preparamos la consulta que previamente vamos a ejecutar
    $resultadoConsulta = $miDB->prepare($sql1);

    //Ejecutamos la consulta
    $resultadoConsulta->execute();

    /**+
     * Mostramos el numero de registros que hemos seleccionado
     * el metodo rowCount() devuelve el numero de filas que tiene la consulta
     */
    $numRegistros = $resultadoConsulta->rowCount();

    //Mediante echo mostranmos la variable que almacena el numero de registros
    echo ('Numero de registros: ' . $numRegistros);

    //Guardo el primer registro como un objeto
    $oResultado = $resultadoConsulta->fetchObject();

    // Inicializamos un array vacío para almacenar todos los departamentos
    $aDepartamentos = [];

    //Inicializamos el contador
    $numeroDepartamento = 0;
    /**
     *Recorro los registros que devuelve la consulta y obtengo por cada valor su resultado
     */
    while ($oResultado) {
        //Guardamos los valores en un array asociativo
        $aDepartamento = [
            'codDepartamento' => $oResultado->T02_CodDepartamento,
            'fechaCreacionDepartamento' => $oResultado->T02_FechaCreacionDepartamento,
            'descDepartamento' => $oResultado->T02_DescDepartamento,
            'volumenNegocio' => $oResultado->T02_VolumenNegocio,
            'fechaBajaDepartamento' => $oResultado->T02_FechaBajaDepartamento
        ];

        // Añadimos el array $aDepartamento al array $aDepartamentos
        $aDepartamentos[] = $aDepartamento;

        //Incremento el contador de departamentos para almacenar informacion el la siguiente posicion        
        $numeroDepartamento++;

        //Guardo el registro actual y avanzo el puntero al siguiente registro que obtengo de la consulta
        $oResultado = $resultadoConsulta->fetchObject();
    }


    /**
     * La funcion json_encode devuelve un string con la representacion JSON
     * Le pasamos el array aDepartamentos y utilizanos el atributo JSON_PRRETY_PRINT para que use espacios en blanco para formatear los datos devueltos.
     * JSON_UNESCAPED_UNICODE: Codifica caracteres Unicode multibyte literalmente
     */
    $json = json_encode($aDepartamentos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    /**
     * Mediante la funcion file_put_contents() podremos escribir informacion en un fichero
     * Pasandole como parametros la ruta donde queresmos que se guarde y el que queremos sobrescribir
     * 
     */
    file_put_contents("../tmp/departamentos.json", $json);

    //Mediante echo mostramos el numero de bytes escritos
    echo ("<br>Exportado correctamente");

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