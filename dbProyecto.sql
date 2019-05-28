create database Proyecto  CHARACTER SET 'UTF8' COLLATE 'utf8_general_ci';
use Proyecto;
CREATE table Persona(
  CI varchar(8),
  Nombres varchar(50),
  Telefono varchar(10),
  Dirrecion varchar(50),
  FechaN date,
  Estado char(1),
  PRIMARY KEY (CI)
 );
CREATE TABLE Departamento(
  Cod tinyint AUTO_INCREMENT,
  Nombres varchar(30),
  Dirrecion varchar(50),
  Fecha date,
  Estado char(1),
  PRIMARY KEY(Cod)
);
 CREATE table Funcionario(
  User  varchar(20),
  Profesion varchar(30),
  FechaI date,
  FechaF date,
  CV text,
  CIP varchar(8),
  CodD tinyint,
  PRIMARY KEY (CIP),
  FOREIGN KEY (CodD) REFERENCES Departamento(Cod)
 );
 CREATE table Usuario(
  Pass varchar(128),
  Estado char(1),
  CIF varchar(8),
  PRIMARY KEY (CIF)
 );
 CREATE table ApiKey(
  Api varchar(128),
  Fecha date,
  Estado char(1),
  CIU varchar(8),
  PRIMARY KEY (CIU)
 );
 CREATE TABLE Proyecto(
  Cod int AUTO_INCREMENT,
  Nombres varchar(50),
  FechaI date,
  FechaF date,
  Descripcion varchar(128),
  Estado char(1),
  PRIMARY KEY(Cod)
);
CREATE table EmProyecto(
  Fecha date,
  CIF varchar(8),
  CodP int,
  FOREIGN KEY (CIF) REFERENCES Funcionario(CIP),
  FOREIGN KEY (CodP) REFERENCES Proyecto(Cod)
 );
 CREATE table Planificacion(
  Cod int AUTO_INCREMENT,
  Objetivo varchar(60),
  Estado char(1),
  CodP int,
  PRIMARY KEY (Cod),
  FOREIGN KEY (CodP) REFERENCES Proyecto(Cod)
 );
 CREATE table Actividad(
  Cod int AUTO_INCREMENT,
  Nombre varchar(50),
  FechaI date,
  FechaF date,
  Estado char(1),
  CodP int,
  PRIMARY KEY (Cod),
  FOREIGN KEY (CodP) REFERENCES Planificacion(Cod)
 );
  CREATE table EmActividad(
  Fecha date,
  CIF varchar(8),
  CodA int,
  FOREIGN KEY (CIF) REFERENCES Funcionario(CIP),
  FOREIGN KEY (CodA) REFERENCES Actividad(Cod)
 );
 
 /*Nota para registrar a una persona ya se necesita un departamento qe exista*/
INSERT INTO Departamento(Nombres,Dirrecion,Fecha,Estado) VALUES ('Informatica','av lujan calle1 n#22','2019-01-01','1'),('Redes y Telecomunicaciones','av lujan calle1 n#22','2019-01-02','1'),('RRHH','av lujan calle1 n#22','2019-01-02','1');
/*en una sola interfaz*/
INSERT INTO Persona(CI,Nombres,Telefono,Dirrecion,FechaN,Estado) VALUES ('8965478','Jose Jimenes Laure','78966336','B/ los olivos','1991-05-05','1');
INSERT INTO Funcionario(User,Profesion,FechaI,FechaF,CV,CIP,CodD) VALUES ('josejl','Analista de Sistemas','2019-01-01','2020-01-01','invente datos :v','8965478','1');
/*para pass use escriptacion*/
INSERT INTO Usuario(Pass,Estado,CIF) VALUES ('12345','1','8965478');
INSERT INTO ApiKey(Api,Fecha,Estado,CIU) VALUES ('555555','2019-01-01','1','8965478');
/* sistema*/
INSERT INTO Proyecto(Nombres,FechaI,FechaF,Descripcion,Estado) VALUES
('Pagina web publicitaria para la venta azucar guaira','2019-01-05','2019-01-22','Desarollo usando informacion de los productos de actuales y usando una promocion qe prorporcionara diche empresa','1'),
('Sistema web para el control y cobro de los Promotores de la CocaCola','2019-01-18','2019-02-05','Informacion prorporcionada por la empresa CocaCola','1'),
('Desarollo aplicacion movil para radiomovil Canario','2019-02-10','2019-02-28','Informacion prorporcionada por el radiomovil','1'),
('Mantenimiento de Sistema de informacion para la administracion y venta de computadora Oktech','2019-03-10','2019-03-15','Informacion prorporcionada por el Oktech','1'),
('Mantenimiento de las camara de seguridad para el supercado Hipermaxi','2019-03-12','2019-03-19','Informacion prorporcionada por el Hipermaxi','1'),
('Auditoria para el sistema de ventas de Santa Monica','2019-03-15','2019-04-01','Informacion prorporcionada por el Santa Monica','1'),
('Sistema de informacion control de ventas de pasajes para Comsur','2019-03-20','2019-04-05','Informacion prorporcionada por el Comsur','1');
INSERT INTO Planificacion(Objetivo,Estado,CodP) VALUES('Desarollo Backend','1','1'),
('Desarollo backend','1','1'),
('Desarollo Frontend','1','1'),
('Testing','1','1'),
('Desarollo Backend','1','2'),
('Desarollo Frontend','1','2'),
('Testing','1','2'),
('Desarollo Backend','1','3'),
('Desarollo Frontend','1','3'),
('Testing','1','3'),
('Inspeccion del sistema','1','4'),
('Testing','1','4'),
('Inspeccion del sistema','1','5'),
('Testing','1','5'),
('Genarar de roportes','1','6'),
('Desarollo Backend','1','7'),
('Desarollo Frontend','1','7'),
('Testing','1','7');

INSERT INTO Actividad(Nombre,FechaI,FechaF,Estado,CodP) VALUES('Entrevista con el cliente','2019-01-05','2019-01-06','1','1'),
('Desarollo de la base datos','2019-01-07','2019-01-09','1','1'),
('Diseño de sitio web','2019-01-10','2019-01-12','1','2'),
('Pruebas del sitio','2019-01-13','2019-01-15','1','2'),
('entrega y prueba con el servidor','2019-01-13','2019-01-15','1','3'),
('Entrevista con el cliente','2019-01-18','2019-01-19','1','4'),
('Visita programada','2019-01-19','2019-01-20','1','4'),
('Desarollo de la base datos','2019-01-21','2019-01-23','1','5'),
('Diseño y desarollo de sitio web','2019-01-23','2019-01-26','1','5'),
('Pruebas del sitio test','2019-01-27','2019-01-29','1','6'),
('entrega y prueba con el servidor','2019-01-30','2019-01-01','1','6');