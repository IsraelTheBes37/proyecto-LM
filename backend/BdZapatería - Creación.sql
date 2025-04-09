DROP DATABASE IF EXISTS `bdzapateria`;
CREATE DATABASE `bdzapateria`;
USE `bdzapateria`;

set foreign_key_checks=0;
--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS clientes;
CREATE TABLE clientes (
  num_cliente int NOT NULL,
  nombre varchar(50) DEFAULT NULL,
  apellido varchar(50) DEFAULT NULL,
  representante int DEFAULT NULL,
  calle varchar(50) DEFAULT NULL,
  porton varchar(50) DEFAULT NULL,
  num_piso varchar(50) DEFAULT NULL,
  cp varchar(50) DEFAULT NULL,
  telefono varchar(50) DEFAULT NULL,
  correo: varchar(50) unique not null,
  clave varchar(50) not null,
  PRIMARY KEY (num_cliente),
  KEY clientes_fk1 (representante),
  CONSTRAINT clientes_fk1 FOREIGN KEY (representante) REFERENCES empleados (num_empleado)
);

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS empleados;
CREATE TABLE empleados (
  num_empleado int NOT NULL,
  nombre varchar(50) DEFAULT NULL,
  apellido varchar(50) DEFAULT NULL,
  cargo varchar(50) DEFAULT NULL,
  correo: varchar(50) unique not null,
  clave varchar(50) not null,
  administrador int DEFAULT NULL,
  PRIMARY KEY (num_empleado),
  CONSTRAINT empleados_fk2 FOREIGN KEY (administrador) REFERENCES empleados (num_empleado)
);

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS pedidos;
CREATE TABLE pedidos (
  num_pedido int NOT NULL,
  fecha_pedido date DEFAULT NULL,
  fk_cliente int DEFAULT NULL,
  fk_vendedor int DEFAULT NULL,
  fk_modelo varchar(50) DEFAULT NULL,
  cantidad int DEFAULT NULL,
  PRIMARY KEY (num_pedido),
  KEY pedidos_fk1 (fk_cliente),
  KEY pedidos_fk2 (fk_modelo),
  KEY pedidos_fk3 (fk_vendedor),
  CONSTRAINT pedidos_fk1 FOREIGN KEY (fk_cliente) REFERENCES clientes (num_cliente),
  CONSTRAINT pedidos_fk2 FOREIGN KEY (fk_modelo) REFERENCES productos (id_modelo),
  CONSTRAINT pedidos_fk3 FOREIGN KEY (fk_vendedor) REFERENCES empleados (num_empleado)
);

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS productos;
CREATE TABLE productos (
  id_modelo varchar(50) NOT NULL,
  descripcion varchar(50) DEFAULT NULL,
  precio int DEFAULT NULL,
  existencias int DEFAULT NULL,
  modelo varchar(50) DEFAULT NULL,
  talla varchar(50) DEFAULT NULL,
  genero varchar(50) DEFAULT NULL,
  PRIMARY KEY (id_modelo)
) ;