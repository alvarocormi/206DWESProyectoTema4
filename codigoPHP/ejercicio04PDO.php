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
    <title>Ejercicio 04 PHP PDO</title>
</head>

<body>
    <header>
        <div class="daw">
            <span>DWES.</span>
        </div>
    </header>
    <main>
        <div class="contenido">
            <h2>Ejercicio 04 PDO</h2>
            <p>Formulario de búsqueda de departamentos por descripción</p>

            <?php
            /**
             * Ejercicio 04
             * 
             * Formulario de búsqueda de departamentos por descripción (por una parte del campo
                  DescDepartamento, si el usuario no pone nada deben aparecer todos los departamentos).

             * 
             * @author Alvaro Cordero Miñambres
             * @version 1.0 
             * @since 07/11/2023
             */

            //Incluimos la libreria de validacion de formularios
            require_once('../core/231018libreriaValidacion.php');

            //Incluimos la configuracion de la base de datos
            require_once '../conf/confDBPDO.php';


            //Este array almacena las respuestas
            $aRespuestas = [
                'DescDepartamento' => '',
            ];

            //Este array almacenara los mensajes de error
            $aErrores = [
                'DescDepartamento' => '',
            ];

            /**
             * Comprobamos si se ha enviado el formulario mediante la funcion isset()
             * isset() -> Comprueba si existe y tiene un valor distinto de null
             */
            if (isset($_REQUEST['enviar'])) {

                //Introducimos valores en el array $aErrores si ocurre un error
                $aErrores = [
                    'DescDepartamento' => validacionFormularios::comprobarAlfaNumerico($_REQUEST['DescDepartamento'], 255, 1, 0),
                ];

                //Recorremos el array de errores
                foreach ($aErrores as $campo => $error) {
                    if ($error == !null) {
                        //Limpiamos el campos
                        $entradaOK = false;
                        $_REQUEST[$campo] = '';
                        //Si ha dado un error la respuesta pasa a valer el valor que ha introducido el usuario
                    } else {
                        $aRespuestas['DescDepartamento'] = $_REQUEST['DescDepartamento'];
                    }
                }
            }


            try {
                //Establecimiento de la conexion
                /*
                Instanciamos un objeto PDO y establecemos la conexión
                Construccion de la cadena PDO: (ej. 'mysql:host=localhost; dbname=midb')
                host – nombre o dirección IP del servidor
                dbname – nombre de la base de datos
                */
                $miDB = new PDO(DSN, USER, PASSWORD);

                //Preparamos la consulta
                $resultadoConsulta = $miDB->query("select * from T02_Departamento where T02_DescDepartamento like'%$aRespuestas[DescDepartamento]%';");

                /**
                 * Si la consulta no devuelve ninguna fila
                 * rowCount() -> Cuenta las filas del resultado de una consulta
                 */
                if ($resultadoConsulta->rowCount() == 0) {

                    //Almacenamos un mensaje de error
                    $aErrores['DescDepartamento'] = "No existen departamentos con esa descripcion";
                }

                //Cargamos los resultados en un fetchobject().
                $oDepartamento = $resultadoConsulta->fetchObject();

                //Creamos una tabla en la que imprimiremos el nombre del atributo y el valor del mismo.
                // Se crea una tabla para imprimir las tuplas
                echo "<table class='table table-bordered' style='width: 55%; margin-top: 70px;'><thead><tr><th>Codigo</th><th>Descripcion</th><th>FechaCreacion</th><th>VolumenNegocio</th><th>FechaBaja</th></tr></thead><tbody>";

                // Se instancia un objeto tipo PDO que almacena cada fila de la consulta
                while ($oDepartamento != null) {
                    echo "<tr>";
                    //Recorrido de la fila cargada
                    echo "<td>$oDepartamento->T02_CodDepartamento</td>"; //Obtener los códigos de los departamentos.
                    echo "<td>$oDepartamento->T02_DescDepartamento</td>"; //Obtener la descripcion de los departamentos. 
                    echo "<td>$oDepartamento->T02_FechaCreacionDepartamento</td>"; //Obtener la fehca de creacion los departamentos.
                    echo "<td>$oDepartamento->T02_VolumenDeNegocio</td>"; //Obtener el volumen de negocio de los departamentos
                    echo "<td>$oDepartamento->T02_FechaBajaDepartamento</td>"; //Obtener la fecha de baja de los departamentos.
                    echo "</tr>";

                    //Pasa a la siguiente fila y devuelve el objeto
                    $oDepartamento = $resultadoConsulta->fetchObject();
                }
                echo "</tbody></table>";

                //Mediante PDOExprecion controlamos los errores

            } catch (PDOException $excepcion) {
                /**
                 * Mostramos los mensajes de error
                 * getMessage() -> Devuelve mensaje de error
                 * getCode() -> Devuelve el codigo del error
                  */
                echo 'Error: ' . $excepcion->getMessage() . "<br>"; //Obtiene el valor de un atributo
                echo 'Código de error: ' . $excepcion->getCode() . "<br>"; // Establece el valor de un atributo

              //Si o si se cierra la sesion  
            } finally {
                //Mediante unset() cerramos la sesion 
                unset($miDB);
            }
            //Despues de que se ejecute el codigo anterioir mostramos pase lo que pase el formulario
            ?>
            <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-check-inline" style="width: 100%; position: fixed; top: 250px; left: 38%">
                <div>
                    <label for="DescDepartamento" style="margin-top: 5px;">Buscar departamento: </label>
                    <input type="text" id="DescDepartamento" name="DescDepartamento" value="<?php echo (isset($_REQUEST['DescDepartamento']) ? $_REQUEST['DescDepartamento'] : ''); ?>">
                    <input type="submit" value="Buscar" name="enviar">
                    <?php echo ($aErrores['DescDepartamento'] != null ? "<span style='color:red'>" . $aErrores['DescDepartamento'] . "</span>" : null); ?>
                </div>
            </form>
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