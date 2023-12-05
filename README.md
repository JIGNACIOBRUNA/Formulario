# Formulario de Votación

Este proyecto consiste en un formulario de votación diseñado para registrar la participación de personas en un evento de votación. El formulario recopila información vital del votante, asegurando la integridad y seguridad del proceso. A continuación, se destacan las principales características del proyecto:

## Funcionalidades Principales:

    Registro de Votos:
        Captura información detallada de cada votante, incluyendo nombre, alias, RUT, correo electrónico, región y comuna de origen.
        Permite a los votantes seleccionar a su candidato preferido de una lista predefinida.

    Validaciones de Seguridad:
        Verifica la duplicación de votos por RUT para garantizar que cada votante participe únicamente una vez.
        Filtra dinámicamente las opciones de comuna según la región seleccionada, proporcionando una experiencia de usuario personalizada y precisa.

    Base de Datos:
        Almacena la información del votante, incluidos los detalles del voto y la forma en que se enteraron del evento, en una base de datos.

    Integración con la Base de Datos:
        Carga dinámicamente las regiones, comunas y candidatos desde la base de datos, asegurando la coherencia y actualización constante de la información.

## Instrucciones de Configuración y Uso:

    Requisitos del Sistema:
        PHP 8.1.10 y PostgreSQL 14 deben estar instalados en el servidor.
        Se recomienda utilizar un servidor web compatible con PHP, como Apache.
        Puedes configurar y ejecutar fácilmente el entorno de desarrollo utilizando herramientas como Laragon          (recomendado), XAMPP o MAMP.

    Pasos de instalacion: 
        Clona este repositorio en tu máquina local:

        ```bash
        git clone https://github.com/tu-usuario/tu-repositorio.git
        ```
        Configura tu entorno local para que pueda ejecutar el proyecto.

        Importa la base de datos proporcionada en el archivo `database.sql` en tu sistema de gestión de bases de datos PostgreSQL.

        Abre el archivo de configuración de la base de datos (`database/conexion.php`) y ajusta los detalles de conexión según tu entorno.

        Abre el proyecto en tu navegador y verifica que todo esté funcionando correctamente.   

    Configuración de la Base de Datos:
        Asegúrese de tener una base de datos PostgreSQL con las tablas necesarias para almacenar la información de Customer, Candidate, Commune, How_do_you_know, Region y Vote.

    Ejecución del Proyecto:
        Configure la conexión a la base de datos en el archivo conexion.php.
        Abra el formulario mediante un servidor web y complete los campos requeridos para registrar un voto.

    Contribuciones:
        Se alienta a otros desarrolladores a contribuir al proyecto mediante la identificación de problemas y la sugerencia de mejoras.