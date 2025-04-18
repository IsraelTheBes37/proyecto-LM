Desglose:

Configurar el archivo conn.php con el servidor de bdd.
Ejecutar el script de la bdzapateria.sql
Utilizar las credeciales de los tres primero usuarios de los insert para probar el sistema.

/frontend: Contiene todos los archivos relacionados con el frontend, incluyendo HTML, CSS, JavaScript, imágenes, fuentes, etc.

CSS y JS se agrupan en carpetas separadas para facilitar la organización.

/assets se usa para recursos como imágenes y fuentes.

/backend: Contiene los archivos PHP del backend. Los subdirectorios ayudan a dividir el código por funcionalidad:

controllers: Controladores que gestionan la lógica del negocio.

models: Modelos para interactuar con la base de datos.

views: Plantillas PHP si se usan.

config.php: Archivo de configuración, por ejemplo, credenciales de base de datos.

index.php: Punto de entrada principal del backend.

/database: Scripts SQL para la creación y configuración de la base de datos.

README.md: Documento para explicar la configuración y uso del proyecto.