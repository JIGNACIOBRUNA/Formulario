<?php
    require_once '../database/conexion.php';
    
    $regiones = pg_fetch_all($resultadoRegiones);
    $comunas = pg_fetch_all($resultadoComunas);
    $candidate = pg_fetch_all($resultadoCandidate);

    // Verifico si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tomo los datos del formulario
    $nombre = $_POST['nombre'];
    $alias = $_POST['alias'];
    $rut = $_POST['rut'];
    $email = $_POST['email'];
    $region = $_POST['Region'];
    $comuna = $_POST['Comuna'];
    $candidato = $_POST['Candidato'];
    $opcion = $_POST['opcion'];

    $queryValidarDuplicacion = "SELECT COUNT(*) FROM \"Customer\" WHERE rut = '$rut'";
    $resultValidarDuplicacion = pg_query($conexion, $queryValidarDuplicacion);
    $countDuplicacion = pg_fetch_result($resultValidarDuplicacion, 0, 0);

    if ($countDuplicacion > 0) {
        echo "<script>alert('Ya existe un voto registrado para el RUT proporcionado.');</script>";
        echo "<script>window.location.href='index.php';</script>";
        exit;
    }    

    // Inserto el cliente en la tabla Customer
    $queryInsertCliente = "INSERT INTO \"Customer\" (name_and_lastname, alias, rut, mail, cod_region, cod_commune) 
                           VALUES ('$nombre', '$alias', '$rut', '$email', '$region', '$comuna') RETURNING codigo";
    //echo "Query Cliente: " . $queryInsertCliente . "<br>";                   
    $resultInsertCliente = pg_query($conexion, $queryInsertCliente);

    // Verifico si la inserción fue exitosa
    if ($resultInsertCliente) {
        // Recupera el código del cliente recién insertado
        $codigoCliente = pg_fetch_result($resultInsertCliente, 0, 'codigo');

        // Inserta el voto en la tabla Vote
        $queryInsertVoto = "INSERT INTO \"Vote\" (cod_customer, cod_candidate) 
                            VALUES ('$codigoCliente', '$candidato')";
        //echo "Query Voto: " . $queryInsertVoto . "<br>";
        $resultInsertVoto = pg_query($conexion, $queryInsertVoto);

        // Verifico si la inserción del voto fue exitosa
        if ($resultInsertVoto) {
            $queryUpdateOpcion = "UPDATE \"How_do_you_know_us\" 
                                  SET $opcion = true 
                                  WHERE cod_customer = '$codigoCliente'";
            echo "Query Opción: " . $queryUpdateOpcion . "<br>";
            $resultUpdateOpcion = pg_query($conexion, $queryUpdateOpcion);

            // Verifico si la actualización de la opción fue exitosa
            if ($resultUpdateOpcion) {
                echo "Voto y opción registrados correctamente.";
            } else {
                echo "Error al actualizar la opción.";
            }
        } else {
            echo "Error al registrar el voto. "   . pg_last_error($conexion);
        }
    } else {
        echo "Error al registrar el cliente."  . pg_last_error($conexion);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
    <link rel="stylesheet" href="style.css">
    <script>
        let Fn = {
            validaRut: function (rutCompleto) {
                if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(rutCompleto))
                    return false;
                var tmp = rutCompleto.split('-');
                var digv = tmp[1];
                var rut = tmp[0];
                if (digv == 'K') digv = 'k';
                return (Fn.dv(rut) == digv);
            },
            dv: function (T) {
                var M = 0, S = 1;
                for (; T; T = Math.floor(T / 10))
                    S = (S + T % 10 * (9 - M++ % 6)) % 11;
                return S ? S - 1 : 'k';
            }
        }
        function validarEmail(email) {
            var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
    </script>
</head>
<body>
    <div class="container">
    <h1>FORMULARIO DE VOTACIÓN:</h1>
    <form action="index.php" method="POST" onsubmit="return validarFormulario()">
        <label>Nombre y Apellido</label>
        <input type="text" id="nombre" name="nombre">
        <br>
        <label>Alias</label>
        <input type="text" id="alias" name="alias" >
        <br>
        <label>RUT</label>
        <input type="num" id="rut" name="rut" >
        <br>
        <label>Email</label>
        <input type="text" id="email" name="email" >
        <br>
        <label for="region">Región</label>
        <select id="region" name="Region" onchange="actualizarComunas()" >
        <option value=""></option>
            <?php foreach ($regiones as $fila) : ?>
                <option value="<?php echo $fila["codigo"]; ?>"><?php echo $fila["name"]; ?>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="comuna">Comuna</label>
        <select id="comuna" name="Comuna" >
        <option value=""></option>
            <?php foreach ($comunas as $fila) : ?>
                <option value="<?php echo $fila["codigo"]; ?>" data-region="<?php echo $fila["cod_region"]; ?>"><?php echo $fila["name"]; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="opciones">Candidato</label>
        <select id="opciones" name="Candidato" >
            <option value=""></option>
            <?php foreach ($candidate as $fila) : ?>
                <option value="<?php echo $fila["codigo"]; ?>"><?php echo $fila["name"] . ' ' . $fila["lastname"]; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label>Como se enteró de Nosotros</label>
            <label>
                <input type="radio" name="opcion" id="web" value="web">    
                Web
            </label>
            <label>
                <input type="radio" name="opcion" id="tv" value="tV">
                TV
            </label>
            <label>
                <input type="radio" name="opcion" id="social_networks" value="social_networks">
                Redes Sociales
            </label>
            <label>
                <input type="radio" name="opcion" id="friend" value="friend">
                Amigo
            </label>
        <br>
        <!-- <input type="submit" name="submit"> -->
        <button>Votar</button>
    </form>
    </div>
    </body>
    <script>
        function validarFormulario() {
            let nombre = document.getElementById('nombre').value;
            let alias = document.getElementById('alias').value;
            let rutInput = document.getElementById('rut').value;
            let email = document.getElementById("email").value;
            let region = document.getElementById("region").value;
            let comuna = document.getElementById("comuna").value;
            let candidato = document.getElementById("opciones").value;
            let opcionWeb = document.getElementById('web').checked;
            let opcionTV = document.getElementById('tv').checked;
            let opcionRedesSociales = document.getElementById('social_networks').checked;
            let opcionAmigo = document.getElementById('friend').checked;


            // Validación de Nombre y Apellido
            if (nombre.trim() === '') {
                alert('Nombre y Apellido no deben quedar en blanco.');
                return false;
            }
            // Validación de Alias
            if (alias.length < 6 || !/^[a-zA-Z0-9]+$/.test(alias)) {
                alert('Alias debe tener al menos 6 caracteres y contener letras y números.');
                return false;
            }//Parte de la validacion del rut
            if (!Fn.validaRut(rutInput)) {
                alert('RUT inválido');
                return false;
            }// Validar Email
            if (!validarEmail(email)) {
                alert('Correo electrónico inválido.');
                return false;
            }if(region.trim() === ""){
                alert("Debe seleccionar una region");
                return false;
            }if(comuna.trim() === ""){
                alert("Debe seleccionar una comuna");
                return false;
            }if(candidato.trim() === ""){
                alert("Debe seleccionar un candidato");
                return false;
            }if (!(opcionWeb || opcionTV || opcionRedesSociales || opcionAmigo)) {
                alert('Debe seleccionar al menos una opción en "Como se enteró de Nosotros".');
                return false;
            }
            
            return true;
        }
    </script>
    <script>
        const actualizarComunas = () => {
            const regionSelect = document.getElementById('region');
            const comunaSelect = document.getElementById('comuna');

            // Limpia las opciones actuales
            comunaSelect.innerHTML = '<option value=""></option>';

            const regionSeleccionada = regionSelect.value;

            console.log(regionSeleccionada);

            // Agregar las nuevas opciones basadas en la región seleccionada
            <?php foreach ($comunas as $fila) : ?>
                if ('<?php echo $fila["cod_region"]; ?>' === regionSeleccionada) {
                    console.log('Comuna Agregada:', '<?php echo $fila["name"]; ?>');
                    const option = document.createElement('option');
                    option.value = '<?php echo $fila["codigo"]; ?>';
                    option.text = '<?php echo $fila["name"]; ?>';
                    comunaSelect.add(option);
                }
            <?php endforeach; ?>
        }
    </script>

</html> 

