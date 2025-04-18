Desglose:

Pasos para ejecutar el programa:
Dirigirse a la carpeta Backend dento se encuentran los archivos "BdZapatería - Creación.sql" y "conn.php".
1. Configurar el archivo "conn.php" con el puerto del servidor de mysql.
2. Copiar el script de "BdZapatería - Creación.sql" y ejecutarlo en MySql, así se creará la bdd y se insertará datos.
3. Los usarios para hacer pruebas son:
	Cliente: jperez@mail.com
	Clave: 1234
	Permisos: puede actulizar sus datos, darse de baja, ver los productos, generar pedidos y devolver pedidos.
	
	Administrador: iquishpe@huellas.com
	Clave: admin
	Permisos: control total para gestionar productos, empleados, pedidos y en clientes no podrá crear nuevos clientes.
	
	Director de ventas: apelaez@huellas.com
	Clave: ventas456
	Permisos: solo puede actulizar clientes y, agregar y editar productos.
	
	Vendedor: ana@huellas.com
	Clave: vendedor123
	Permisos: tiene control total sobre pedidos.

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