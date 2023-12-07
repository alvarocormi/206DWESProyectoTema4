<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Fuentes -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../webroot/css/proyectoTema4.css" />
        <link rel="stylesheet" href="../../webroot/css/main.css" />
        <!--Boostrap-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <title>Ejercicio 05 PHP PDO</title>
    </head>

    <body>
        <header>
            <div class="daw">
                <span>DWES.</span>
            </div>
        </header>
        <main>
            <div class="contenido">
                <h2>Ejercicio 05 PDO</h2>
                <p>Pagina web que añade tres registros a nuestra tabla Departamento</p>
                <?php
                /**
                 * @author Carlos García Cachón, Alvaro Cordero
                 * @version 1.0
                 * @since 08/11/2023
                 */
                
                // Incluyo la libreria de validación para comprobar los campos
                require_once '../core/231018libreriaValidacion.php';
                // Incluyo la configuración de conexión a la BD
                require_once '../conf/confDB.php';

                try {
                    // CONEXION CON LA BD
                    // Establecemos la conexión por medio de PDO
                    $miDB = new PDO(DSN, USER, PASSWORD);
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configuramos las excepciones
                    echo ("<div>CONEXIÓN EXITOSA POR PDO</div><br><br>"); // Mensaje si la conexión es exitosa
                    // CONSULTAS Y TRANSACCION
                    $miDB->beginTransaction(); // Deshabilitamos el modo autocommit
                    
                    // Consultas SQL de inserción 
                    $consultaInsercion1 = "INSERT INTO T02_Departamento (T02_CodDepartamento, T02_FechaCreacionDepartamento, T02_DescDepartamento, T02_VolumenNegocio, T02_FechaBajaDepartamento)VALUES ('IOP', '2023-11-17 08:15:00', 'Departamento de Finanzas', 350000.0, NULL);";
                    $consultaInsercion2 = "INSERT INTO T02_Departamento (T02_CodDepartamento, T02_FechaCreacionDepartamento, T02_DescDepartamento, T02_VolumenNegocio, T02_FechaBajaDepartamento)VALUES ('PÑM', '2023-11-18 10:30:00', 'Departamento de Logística', 280000.0, NULL);";
                    $consultaInsercion3 = "INSERT INTO T02_Departamento (T02_CodDepartamento, T02_FechaCreacionDepartamento, T02_DescDepartamento, T02_VolumenNegocio, T02_FechaBajaDepartamento)VALUES ('GHJ', '2023-11-19 13:45:00', 'Departamento de Investigación y Desarrollo', 420000.0, NULL);";

                    // Preparamos las consultas
                    $resultadoconsultaInsercion1 = $miDB->prepare($consultaInsercion1);
                    $resultadoconsultaInsercion2 = $miDB->prepare($consultaInsercion2);
                    $resultadoconsultaInsercion3 = $miDB->prepare($consultaInsercion3);

                    // Ejecuto las consultas preparadas y mostramos la tabla en caso 'true' o un mensaje de error en caso de 'false'.
                    // (La función 'execute()' devuelve un valor booleano que indica si la consulta se ejecutó correctamente o no.)
                    if ($resultadoconsultaInsercion1->execute() && $resultadoconsultaInsercion2->execute() && $resultadoconsultaInsercion3->execute()) {
                        $miDB->commit(); // Confirma los cambios y los consolida
                        echo ("<div>Los datos se han insertado correctamente en la tabla Departamento.</div>");

                        // Preparamos y ejecutamos la consulta SQL
                        $consulta = "SELECT * FROM T02_Departamento";
                        $resultadoConsultaPreparada = $miDB->prepare($consulta);
                        $resultadoConsultaPreparada->execute();

                        // Creamos una tabla en la que mostraremos la tabla de la BD
                        echo ("<div class='list-group text-center'>");
                        echo ("<table>
                                        <thead>
                                        <tr>
                                            <th>CodDepartamento</th>
                                            <th>FechaCreacion</th>
                                            <th>DescDepartamento</th>
                                            <th>VolumenNegocio</th>
                                            <th>FechaBaja</th>
                                        </tr>
                                        </thead>");

                        /* Aqui recorremos todos los valores de la tabla, columna por columna, usando el parametro 'PDO::FETCH_ASSOC' , 
                         * el cual nos indica que los resultados deben ser devueltos como un array asociativo, donde los nombres de las columnas de 
                         * la tabla se utilizan como claves (keys) en el array.
                         */
                        echo ("<tbody>");
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
                        echo ("<tfoot ><tr style='background-color: #666; color:white;'><td colspan='4'>Número de registros en la tabla Departamento: " . $numeroDeRegistrosConsultaPreparada . '</td></tr></tfoot>');
                        echo ("</table>");
                        echo ("</div>");
                    }
                } catch (PDOException $miExcepcionPDO) {
                    $miDB->rollback(); //  Revierte o deshace los cambios
                    $errorExcepcion = $miExcepcionPDO->getCode(); // Almacenamos el código del error de la excepción en la variable '$errorExcepcion'
                    $mensajeExcepcion = $miExcepcionPDO->getMessage(); // Almacenamos el mensaje de la excepción en la variable '$mensajeExcepcion'

                    echo ("<div class='errorException'>Hubo un error al insertar los datos en la tabla Departamento.<br></div>");
                    echo "<span class='errorException'>Error: </span>" . $mensajeExcepcion . "<br>"; // Mostramos el mensaje de la excepción
                    echo "<span class='errorException'>Código del error: </span>" . $errorExcepcion; // Mostramos el código de la excepción
                } finally {
                    unset($miDB); // Para cerrar la conexión
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