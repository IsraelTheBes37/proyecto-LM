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
  nom_imagen varchar(300) DEFAULT NULL,
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
VALUES ('Israel', 'Quishpe', 'Informatico', 'iquishpe@huellas.com', 'admin', NULL);

-- Insertar al vendedor (administrado por el Informático)
INSERT INTO empleados (nombre, apellido, cargo, correo, clave, administrador)
VALUES ('Ana', 'López', 'Vendedor', 'ana@huellas.com', 'vendedor123', 1);

-- Insertar al Director de Ventas (administrado por el Informático)
INSERT INTO empleados (nombre, apellido, cargo, correo, clave, administrador)
VALUES ('Alejandro', 'Peláez', 'DirectorVentas', 'apelaez@huellas.com', 'ventas456', 1);

-- 7 empleados mas insertados: 
INSERT INTO empleados (nombre, apellido, cargo, correo, clave, administrador) VALUES
('Carlos', 'Mendoza', 'Vendedor', 'cmendoza@huellas.com', 'clave123', 3),
('Lucía', 'Ramírez', 'Vendedor', 'lramirez@huellas.com', 'clave456', 3),
('Marta', 'González', 'Vendedor', 'mgonzalez@huellas.com', 'clave789', 3),
('Pedro', 'Salas', 'Vendedor', 'psalas@huellas.com', 'clave147', 3),
('Luis', 'Alarcón', 'Vendedor', 'lalarcon@huellas.com', 'clave258', 3),
('Paola', 'Cedeño', 'Vendedor', 'pcedeno@huellas.com', 'clave369', 3),
('Andrea', 'Villamar', 'Vendedor', 'avillamar@huellas.com', 'clave741', 3);

INSERT INTO clientes (nombre, apellido, representante, calle, porton, num_piso, cp, telefono, correo, clave) VALUES
('Juan', 'Pérez', 2, 'Av. Quito', '123A', '2', '170515', '0991234567', 'jperez@mail.com', '1234'),
('María', 'López', 2, 'Calle Bolívar', '45B', '1', '170102', '0998765432', 'mlopez@mail.com', 'abcd'),
('Carlos', 'Martínez', 4, 'Av. Amazonas', '12C', '5', '170201', '0990001111', 'cmartinez@mail.com', '5678'),
('Lucía', 'Reyes', 5, 'Calle Manabí', '33', '3', '170112', '0991112222', 'lreyes@mail.com', 'clave'),
('Fernanda', 'Sánchez', 4, 'Av. América', '77B', '6', '170321', '0993334444', 'fsanchez@mail.com', 'fs2024'),
('Jorge', 'Vega', 6, 'Calle Olmedo', '90D', '4', '170611', '0995556666', 'jvega@mail.com', 'clave1'),
('Gabriela', 'Morales', 6, 'Av. Colón', '11A', '3', '170413', '0997778888', 'gmorales@mail.com', 'clave2'),
('Esteban', 'Paredes', 5, 'Calle Tulcán', '222', '2', '170703', '0999990000', 'eparedes@mail.com', 'clave3'),
('Andrea', 'García', 7, 'Av. Eloy Alfaro', '16C', '1', '170314', '0981234567', 'agarcia@mail.com', 'clave4'),
('David', 'Mora', 7, 'Calle Cuenca', '88Z', '1', '170802', '0989876543', 'dmora@mail.com', 'clave5');

INSERT INTO productos (nom_imagen, descripcion, precio, existencias, modelo, talla, genero) VALUES
('zapato1.jpg', 'Zapato casual negro', 45, 20, 'ZP001', '40', 'Masculino'),
('zapato2.jpg', 'Zapato formal café', 55, 15, 'ZP002', '42', 'Masculino'),
('zapato3.jpg', 'Zapato deportivo blanco', 60, 30, 'ZP003', '38', 'Femenino'),
('zapato4.jpg', 'Sandalia azul', 35, 25, 'ZP004', '37', 'Femenino'),
('zapato5.jpg', 'Botín negro', 70, 10, 'ZP005', '41', 'Masculino'),
('zapato6.jpg', 'Zapatilla rosada', 50, 18, 'ZP006', '36', 'Femenino'),
('zapato7.jpg', 'Zapato escolar', 40, 40, 'ZP007', '39', 'Unisex'),
('zapato8.jpg', 'Zapato trekking', 80, 12, 'ZP008', '43', 'Masculino'),
('zapato9.jpg', 'Sandalia con tacón', 65, 8, 'ZP009', '38', 'Femenino'),
('zapato10.jpg', 'Zapato de cuero', 90, 5, 'ZP010', '44', 'Masculino');

INSERT INTO pedidos (fecha_pedido, fk_cliente, fk_vendedor, fk_modelo, cantidad) VALUES
('2025-04-01', 1, 2, 1, 2),
('2025-04-02', 2, 4, 3, 1),
('2025-04-03', 3, 5, 2, 3),
('2025-04-04', 4, 4, 4, 1),
('2025-04-05', 5, 6, 5, 1),
('2025-04-06', 6, 6, 6, 2),
('2025-04-07', 7, 5, 7, 1),
('2025-04-08', 8, 7, 8, 2),
('2025-04-09', 9, 7, 9, 1),
('2025-04-10', 10, 4, 10, 1);
