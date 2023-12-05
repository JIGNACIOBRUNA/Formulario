<?php

$config = include('config.php');

$host = $config['host'];
$dbname = $config['dbname'];
$user = $config['user'];
$password = $config['password'];

$conexion=pg_connect("host=$host dbname=$dbname user=$user password=$password"); 

// if($conexion){
//     echo "Conexion exitosa";
// }else{
//     echo "No esta conectado";
// }

$consulta='SELECT name_and_lastname, alias,rut,mail FROM
           "Customer"';

$resultado = pg_query($conexion, $consulta);

// if($resultado){

//     $datos = pg_fetch_all($resultado);

//     // Imprimir los resultados
//     if ($datos) {
//         foreach ($datos as $fila) {
//             echo " <br> Nombre y Apellido: " . $fila["name_and_lastname"] . "<br>";
//             echo "Alias: " . $fila['alias'] . "<br>";
//             echo "RUT: " . $fila['rut'] . "<br>";
//             echo "Correo: " . $fila['mail'] . "<br>";
//             echo "Candidato: " . $fila['candidate'] . "<br>";
//             echo "----------------------<br>";
//         }
//     } else {
//         echo "No se encontraron registros en la tabla Customer.";
//     }
// } else {
//     echo "Error al ejecutar la consulta.";
    
// }

$consultaRegiones = 'SELECT * FROM "Region"';
$resultadoRegiones = pg_query($conexion, $consultaRegiones);
$regiones = pg_fetch_all($resultadoRegiones);

// if($regiones){
//     foreach($regiones as $fila){
//         echo "Nombre: " .$fila["name"] . "<br>";
//         echo "----------------------<br>";
//     }
// }else{
//     echo "No se encontraron registros en la tabla Region";
// }

// Obtener comunas
$consultaComunas = 'SELECT * FROM "Commune"';
$resultadoComunas = pg_query($conexion, $consultaComunas);
$comunas = pg_fetch_all($resultadoComunas);

// if($comunas){
//     foreach($comunas as $fila){
//         echo "Nombre: " .$fila["name"] . "<br>";
//         echo "----------------------<br>";
//     }
// }else{
//     echo "No se encontraron registros en la tabla Commune";
// }

//Obtener candidatos
$consultaCandidate = 'SELECT * FROM "Candidate"';
$resultadoCandidate = pg_query($conexion, $consultaCandidate);
$candidate = pg_fetch_all($resultadoCandidate);


// class Conexion{

//     function ConexionBD(){
//         $host = "localhost";
//         $nombre_bd = "formulario";
//         $usuario = "postgres";
//         $contrasena = "lucy";
        
//         try {
//             $conexion = pg_connect("pgsql:host=$host;dbname=$nombre_bd", $usuario, $contrasena);
//             // Configura el modo de errores para que PDO lance excepciones en lugar de warnings.
//             $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//             echo "Conexión exitosa";
//         } catch (PDOException $e) {
//             echo "Error de conexión: " . $e->getMessage();
//         }
//         return $conexion;
//     }
// }
?>
