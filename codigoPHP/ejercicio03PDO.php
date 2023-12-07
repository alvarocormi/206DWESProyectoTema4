<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../webroot/css/proyectoTema4.css" />
    <!--Boostrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../webroot/css/main.css" />
    <title>Ejercicio 03 PHP PDO</title>
</head>

<body>
    <header>
        <div class="daw">
            <span>DWES.</span>
        </div>
    </header>
    <main>
        <div class="contenido">
            <h2>Ejercicio 03 PDO</h2>
            <p>Formulario para añadir un departamento a la tabla Departamento con validación de entrada</p>
            <?php
            /**
             * Ejercicio 03
             * 
             * Formulario para añadir un departamento a la tabla Departamento con validación de entrada
             * 
             * @author Alvaro Cordero Miñambres, Rebeca
             * @version 1.0 
             * @since 13/11/2023
             */

            //Incluimos la libreria de validacion de formularios
            require_once('../core/231018libreriaValidacion.php');

            //Incluimos la configuracion de la base de datos
            require_once '../conf/confDB.php';

            //Inicializacion de variables
            $entradaOK = true; //Indica si todas las respuestas son correctas

            /**
             * Inicializamos el array de respuestas
             * La fechaBaja es null siempre
             * La fechaCreacion va a contener la fecha y la hora del momento en el que se creo un departamento
             */
            $aRespuestas = [
                'codDepartamento' => '',
                'fechaCreacionDepartamento' => 'now()',
                'descDepartamento' => '',
                'volumenNegocio' => '',
                'fechaBaja' => 'null'
            ];

            // El array $aErrores almacena los valores que no cumplan los requisitos que se hayan introducido en el formulario
            // No se incluyen la fecha de creacion ni la fecha de baja porque no se validan 
            $aErrores = [
                'codDepartamento' => '',
                'descDepartamento' => '',
                'volumenNegocio' => ''
            ];

            // Si el fromulario ha sido rellenado, se valida la entrada
            if (isset($_REQUEST['enviar'])) {
                // VALIDACIONES
                // Se comprueba que el valor introducido en el campo 'codDepartamento' sea un valor alfabetico con longitud de 3 caracterres, si no lo es, se añade un mensaje de error al array $aErrores
                $aErrores['codDepartamento'] = validacionFormularios::comprobarAlfabetico($_REQUEST['codDepartamento'], 3, 3, 1);

                // Se comprueba que el codigo de departamento no exista en la base de datos
                if ($aErrores['codDepartamento'] == null) {
                    //Añadimos un bloque try catch para el control de errores
                    try {
                        // Se instancia un objeto tipo PDO que establece la conexion a la base de datos con el usuario especificado
                        $miDB = new PDO(DSN, USER, PASSWORD);

                        // Se configuran las excepciones
                        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Se prepara la consulta que filtra los departamentos por codigo
                        $consultaPorCodigo = $miDB->prepare('SELECT * FROM T02_Departamento WHERE T02_CodDepartamento = "' . $_REQUEST['codDepartamento'] . '";');

                        // Se ejecuta la query
                        $consultaPorCodigo->execute();

                        //Si la consulta devuelve alguna fila añadirmos un error al array errores
                        if ($consultaPorCodigo->rowCount() > 0) {
                            $aErrores['codDepartamento'] = "Ese codigo de departamento ya existe";
                        }

                        //Mediante PDOException controlamos los errores   
                    } catch (PDOException $miExcepcionPDO) {
                        /**
                         * Mostramos los mensajes de error
                         * getMessage() -> Devuelve mensaje de error
                         * getCode() -> Devuelve el codigo del error
                         */
                        echo $miExcepcionPDO->getCode();
                        echo $miExcepcionPDO->getMessage();

                        //Pase lo que pase cerramos sesion en la base de datos
                    } finally {
                        //Mediante unset podremos cerrar sesion
                        unset($miDB);
                    }
                }

                /**
                 *  Se comprueba que el valor introducido en el campo 'descDepartamento' sea un valor alfabetico con longitud maxima de 255 caracterres, si no lo es, se añade un mensaje de error al array $aErrores
                 */
                $aErrores['descDepartamento'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['descDepartamento'], 255, 1, 1);

                // Se comprueba que el valor introducido en el campo 'volumenNegocio' sea un numero real, si no lo es, se añade un mensaje de error al array $aErrores
                $aErrores['volumenNegocio'] = validacionFormularios::comprobarFloat($_REQUEST['volumenNegocio'], PHP_FLOAT_MAX, -PHP_FLOAT_MAX, 1);

                // Se recorre el array de errores 
                foreach ($aErrores as $campo => $error) {
                    // Si existe algun error se cambia el valor de $entradaOK a false y se limpia ese campo
                    if ($error != null) {
                        $_REQUEST[$campo] = '';
                        $entradaOK = false;
                    }
                }

                // Si el formulario NUNCA ha sido rellenado, se inicializa $entradaOK a false    
            } else {
                $entradaOK = false;
            }

            // Si todos los valores intruducidos son correctos, se muestran los campos del formulario y sus respuestas
            if ($entradaOK) {
                // TRATAMIENTO DE DATOS
                // Se añaden al array $aRespuestas las respuestas cuando son correctas
                $aRespuestas['codDepartamento'] = $_REQUEST['codDepartamento'];
                $aRespuestas['descDepartamento'] = $_REQUEST['descDepartamento'];
                $aRespuestas['volumenNegocio'] = $_REQUEST['volumenNegocio'];

                // Se ataca a la base de datos
                try {
                    // Se instancia un objeto tipo PDO que establece la conexion a la base de datos con el usuario especificado
                    $miDB = new PDO(DSN, USER, PASSWORD);

                    // SOLUCION CON EXEC()
                    // Se almacena el resultado de la consulta de insercion

                    $numRegistros = $miDB->exec('insert into T02_Departamento values ("' . $aRespuestas['codDepartamento'] . '",' . $aRespuestas['fechaCreacionDepartamento'] . ',"' . $aRespuestas['descDepartamento'] . '",' . $aRespuestas['volumenNegocio'] . ',' . $aRespuestas['fechaBaja'] . ');');
                    // Se almacena el resultado de la consulta select
                    $resultadoConsulta = $miDB->query('select * from T02_Departamento');

                    // SOLUCION CON PREPARE()
                    // Se inicializa la consulta de insercion
                    //$consulta = 'INSERT INTO Departamento VALUES ("'.$aRespuestas['codDepartamento'].'","'.$aRespuestas['fechaCreacionDepartamento'].'",'.$aRespuestas['descDepartamento'].','.$aRespuestas['volumenNegocio'].','.$aRespuestas['fechaBaja'].')';
                    // Se almacena el resultado de la consulta
                    //$resultadoConsultaPrepare = $miDB->prepare($consulta);
                    // Se ejecuta la consulta
                    //$resultadoConsultaPrepare->execute();
                    // Se almacena el resultado de la consulta select
                    //$resultadoConsulta = $miDB->prepare('select * from Departamento');
                    // Se ejecuta la query de seleccion
                    //$resultadoConsulta->execute();

                    //Se almacena el numero de filas afectadas
                    //Se muestra por pantalla el numero de tuplas de la tabla departamentos
                    printf("<p style='color: black;'>Número de registros: %s</p><br>", $resultadoConsulta->rowCount());

                    // Se crea una tabla para imprimir las tuplas
                    echo "<table class='table table-bordered' style='width: 50%;'><thead><tr><th>Codigo</th><th>FechaCreacion</th><th>Descripcion</th><th>VolumenNegocio</th><th>FechaBaja</th></tr></thead><tbody>";
                    // Se instancia un objeto tipo PDO que almacena cada fila de la consulta
                    while ($oDepartamento = $resultadoConsulta->fetchObject()) {
                        echo "<tr>";
                        //Recorrido de la fila cargada
                        echo "<td>$oDepartamento->T02_CodDepartamento</td>"; //Obtener los códigos de los departamentos.
                        echo "<td>$oDepartamento->T02_FechaCreacionDepartamento</td>"; //Obtener la fehca de creacion los departamentos.
                        echo "<td>$oDepartamento->T02_DescDepartamento</td>"; //Obtener la descripcion de los departamentos. 
                        echo "<td>$oDepartamento->T02_VolumenNegocio</td>"; //Obtener el volumen de negocio de los departamentos
                        echo "<td>$oDepartamento->T02_FechaBajaDepartamento</td>"; //Obtener la fecha de baja de los departamentos.
                        echo "</tr>";
                    }
                    echo "</tbody></table>";

                    // Se cierra la conexion con la base de datos
                    unset($miDB);
                } catch (PDOException $excepcion) {
                     /**
                         * Mostramos los mensajes de error
                         * getMessage() -> Devuelve mensaje de error
                         * getCode() -> Devuelve el codigo del error
                         */
                    echo 'Error: ' . $excepcion->getMessage() . "<br>";
                    echo 'Código de error: ' . $excepcion->getCode() . "<br>";
                }

                // Si no son correctos los valores de entrada, se muestra el formulario    
            } else {
            ?>
                <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-check-inline" style="width: 100%; position: fixed; top: 250px; left: 42%">
                    <div>
                        <label for="codigo">CodDepartamento: </label>
                        <input type="text" name="codDepartamento" style="background-color: #fcfbc2; width: 80px;" id="codDepartamento" class="iObligatorio" value="<?php echo (isset($_REQUEST['codDepartamento']) ? $_REQUEST['codDepartamento'] : '') ?>">
                        <?php echo ($aErrores['codDepartamento'] != null ? "<span style='color:red'>" . $aErrores['codDepartamento'] . "</span>" : null); ?>
                        <br><br>

                        <label for="fechaCreacion">FechaCreacion: </label>
                        <input type="text" name="fechaCreacionDepartamento" style="background-color: #D2D2D2; width: 160px;" id="fechaCreacionDepartamento" value="<?php echo ($oFecha = date('Y-m-d H:i:s')) ?>" disabled>
                        <br><br>

                        <label for="descripcion" style="margin-top: 5px;">DescDepartamento: </label>
                        <input type="text" name="descDepartamento" style="background-color: #fcfbc2; width: 360px;" id="descDepartamento" class="iObligatorio" value="<?php echo (isset($_REQUEST['descDepartamento']) ? $_REQUEST['descDepartamento'] : '') ?>">
                        <?php echo ($aErrores['descDepartamento'] != null ? "<span style='color:red'>" . $aErrores['descDepartamento'] . "</span>" : null); ?>
                        <br><br>

                        <label for="volumenNegocio" style="margin-top: 5px;">VolumenNegocio: </label>
                        <input type="text" name="volumenNegocio" id="volumenNegocio" style="background-color: #fcfbc2; width: 120px;" class="iObligatorio" value="<?php echo (isset($_REQUEST['volumenNegocio']) ? $_REQUEST['volumenNegocio'] : '') ?>">
                        <?php echo ($aErrores['volumenNegocio'] != null ? "<span style='color:red'>" . $aErrores['volumenNegocio'] . "</span>" : null); ?>
                        <br><br>

                        <label for="fechaBaja" style="margin-top: 5px;">FechaBaja: </label>
                        <input type="text" style="background-color: #D2D2D2;  width: 160px;" id="FechaBaja" name="fechaBaja" value="<?php echo ('') ?>" disabled>
                        <br><br>


                        <input type="submit" value="Enviar" name="enviar">
                    </div>
                </form>

            <?php
            }
            ?>



        </div>

    </main>

    <footer style="position: fixed;
                bottom: 0;
                right: 0;
                width: 100%;">
        <div class="enlaces-footer">
            <a href="../../index.html" style="color: #737373; text-decoration: none; font-size: 20px">
                © Alvaro Cordero</a>
            <a href="../indexProyectoTema4.html">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#737373" class="bi bi-house-fill" viewBox="0 0 16 16">
                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"></path>
                    <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"></path>
                </svg>
            </a>
            <a href="https://github.com/alvarocormi/206DWESProyectoTema4" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#737373" class="bi bi-github" viewBox="0 0 16 16">
                    <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path>
                </svg></a>
            <a href="https://es.linkedin.com/in/%C3%A1lvaro-cordero-mi%C3%B1ambres-2a1893233" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#737373" class="bi bi-linkedin" viewBox="0 0 16 16">
                    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z" />
                </svg>
            </a>
        </div>
    </footer>
</body>


</html>