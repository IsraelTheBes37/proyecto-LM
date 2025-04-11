DROP DATABASE IF EXISTS `bdzapateria`;
CREATE DATABASE `bdzapateria`;
USE `bdzapateria`;

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS empleados;
CREATE TABLE empleados (
  num_empleado int NOT NULL AUTO_INCREMENT,
  nombre varchar(50) DEFAULT NULL,
  apellido varchar(50) DEFAULT NULL,
  cargo varchar(50) DEFAULT NULL,
  correo varchar(50) UNIQUE DEFAULT NULL,
  clave varchar(50) not null,
  administrador int DEFAULT NULL,
  PRIMARY KEY (num_empleado),
  CONSTRAINT empleados_fk2 FOREIGN KEY (administrador) REFERENCES empleados (num_empleado)
);
--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS clientes;
CREATE TABLE clientes (
  num_cliente int NOT NULL AUTO_INCREMENT,
  nombre varchar(50) DEFAULT NULL,
  apellido varchar(50) DEFAULT NULL,
  representante int DEFAULT NULL,
  calle varchar(50) DEFAULT NULL,
  porton varchar(50) DEFAULT NULL,
  num_piso varchar(50) DEFAULT NULL,
  cp varchar(50) DEFAULT NULL,
  telefono varchar(50) DEFAULT NULL,
  correo varchar(50) UNIQUE DEFAULT NULL,
  clave varchar(50) not null,
  PRIMARY KEY (num_cliente),
  CONSTRAINT clientes_fk1 FOREIGN KEY (representante) REFERENCES empleados (num_empleado)
);

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS productos;
CREATE TABLE productos (
  id_modelo int PRIMARY KEY AUTO_INCREMENT,
  descripcion varchar(50) DEFAULT NULL,
  precio int DEFAULT NULL,
  existencias int DEFAULT NULL,
  modelo varchar(50) DEFAULT NULL,
  talla varchar(50) DEFAULT NULL,
  genero varchar(50) DEFAULT NULL
) ;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS pedidos;
CREATE TABLE pedidos (
  num_pedido int NOT NULL AUTO_INCREMENT,
  fecha_pedido date DEFAULT NULL,
  fk_cliente int DEFAULT NULL,
  fk_vendedor int DEFAULT NULL,
  fk_modelo int DEFAULT NULL,
  cantidad int DEFAULT NULL,
  PRIMARY KEY (num_pedido),
  CONSTRAINT pedidos_fk1 FOREIGN KEY (fk_cliente) REFERENCES clientes (num_cliente),
  CONSTRAINT pedidos_fk2 FOREIGN KEY (fk_modelo) REFERENCES productos (id_modelo),
  CONSTRAINT pedidos_fk3 FOREIGN KEY (fk_vendedor) REFERENCES empleados (num_empleado)
);

-- Asegúrate de tener la tabla creada antes de ejecutar esto

-- Insertar al administrador (Informático)
INSERT INTO empleados (nombre, apellido, cargo, correo, clave, administrador)
VALUES ('Israel', 'Quishpe', 'Informático', 'iquishpe@empresa.com', 'admin', NULL);

-- Insertar al vendedor (administrado por el Informático)
INSERT INTO empleados (nombre, apellido, cargo, correo, clave, administrador)
VALUES ('Ana', 'López', 'Vendedor', 'ana@empresa.com', 'vendedor123', 1);

-- Insertar al Director de Ventas (administrado por el Informático)
INSERT INTO empleados (nombre, apellido, cargo, correo, clave, administrador)
VALUES ('Alejandro', 'Peláez', 'DirectorVentas', 'apelaez@empresa.com', 'ventas456', 1);