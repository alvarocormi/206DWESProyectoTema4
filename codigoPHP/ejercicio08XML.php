<?php
//Incluimos el head
require_once("./head.php");
?>

<title>Ejercicio 08 PHP XML</title>

<?php
//Incluimos el header
require_once("./header.php");
?>

<h2>Ejercicio 08 XML</h2>
<p>PDO USANDO LA EXPORTACION CON XML</p>

    <?php
    /**
     * @author Alvaro Cordero
     * @version 1.0
     * @since 20/11/2023
     * 
     * @Annotation Exportando archivos en formato .xml
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
    require_once '../conf/confDBPDOExplotacion.php';

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

        /**
         * Instanciamos el nuevo documento usando el objeto DOMDocument
         * Le asignamos dos parametros -> Version, Codificacion XML
         */
        $archivoXML = new DOMDocument("1.0", "utf-8");

        //Le decimos que queremos formatear el codigo poniendo a true la propiedad formatOutput
        $archivoXML->formatOutput = true;

        /**Creo el nodo raiz departamentos del de dependeran los demas
         * createElement() -> Crea un nuevo nodo elemento
         * En este caso le pasamos como parametro el nombre del elemento
         * */
        $nDepartamentos = $archivoXML->createElement('Departamentos');

        /**Introduzco el nodo raiz en el archivo
         * appenChild() -> Añade un nuevo hijo al final de los hijos
         */
        $root = $archivoXML->appendChild($nDepartamentos);

        //Guardo el primer registro como un objeto
        $oResultado = $resultadoConsulta->fetchObject();

        /**
         *Recorro los registros que devuelve la consulta y obtengo por cada valor su resultado
         */
        while ($oResultado) {
            //Guardamos los valores en un array asociativo
            //Creo el nodo departamento para cada uno de ellos
            $nDepartamento = $root->appendChild($archivoXML->createElement('Departamento'));

            //Creo el elemento con el nombre CodDepartamento y despues el valor obtenido de la consulta
            $nDepartamento->appendChild($archivoXML->createElement('CodDepartamento', $oResultado->T02_CodDepartamento));

            //Creo el elemento con el nombre FechaCreacion Departamento y despues el valor obtenido de la consulta
            $nDepartamento->appendChild($archivoXML->createElement('FechaCreacionDepartamento', $oResultado->T02_FechaCreacionDepartamento));

            //Creo el elemento con el nombre DescDepartamento y despues el valor obtenido de la consulta
            $nDepartamento->appendChild($archivoXML->createElement('DescDepartamento', $oResultado->T02_DescDepartamento));

            //Creo el elemento con el nombre VolumenNegocio y despues el valor obtenido de la consulta          
            $nDepartamento->appendChild($archivoXML->createElement('VolumenNegocio', $oResultado->T02_VolumenNegocio));

            /**
             * A la fechaBaja no le soy valor porque por defecto es null.
             */
            $nDepartamento->appendChild($archivoXML->createElement('FechaBajaDepartamento'));

            //Guardo el registro actual y avanzo el puntero al siguiente registro que obtengo de la consulta
            $oResultado = $resultadoConsulta->fetchObject();
        }

        /**
         * Guardamos el archivo en la ruta indicada
         * save() -> Copia el árbol XML interno a un archivo
         */
        $archivoXML->save('../tmp/departamentos.xml');

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