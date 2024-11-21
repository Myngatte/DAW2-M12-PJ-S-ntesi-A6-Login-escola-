create database bd_escuela;
use bd_escuela;
create table tbl_usuario(
    id_usuario int auto_increment primary key not null,
    usuario_escuela  varchar(50) not null,
    nom_usuario varchar(30) not null,
    ape_usuario varchar(30) not null,
    contra_usuario varchar(255) not null,
    telefono_usuario char(9) not null,
    fecha_nacimi_usuario date not null,
    sexo_usuario  enum('M','F') null,
    rol_user  INT not null
);

create table tbl_rol(
    id_rol int auto_increment primary key not null,
    nombre_rol Varchar(20)
);

create table tbl_notas(
    id_notas int auto_increment primary key not null,
    id_materia int not null,
    id_user int not null,
    nota Decimal null
);

create tbl_materia(
    id_materia int auto_increment primary key not null,
    nombre_materia
);


ALTER TABLE tbl_usuario
ADD CONSTRAINT fk_rol_user
FOREIGN KEY (rol_user) REFERENCES tbl_rol(id_rol);


ALTER TABLE tbl_notas
ADD CONSTRAINT fk_notas_user
FOREIGN KEY (id_user) REFERENCES tbl_usuario(id_usuario);

ALTER TABLE tbl_notas
ADD CONSTRAINT fk_notas_materia
FOREIGN KEY (id_materia) REFERENCES tbl_materia(id_materia);


INSERT INTO tbl_rol (nombre_rol)
VALUES 
('admin'),
('alumno');

INSERT INTO tbl_usuario (usuario_escuela, nom_usuario, ape_usuario, contra_usuario, telefono_usuario, fecha_nacimi_usuario, sexo_usuario, rol_user) 
VALUES 
('jperez', 'Juan', 'Perez', PASSWORD_HASH('Colegio123.', 'SHA256');, '987654321', '1990-01-01', 'M', 1),
('mrodriguez', 'Maria', 'Rodriguez', PASSWORD_HASH('Colegio123.', 'SHA256'), '987654322', '1995-06-15', 'F', 2),
('plopez', 'Pedro', 'Lopez', PASSWORD_HASH('Colegio123.', 'SHA256'), '987654323', '1980-03-20', 'M', 2),
('agarcia', 'Ana', 'Garcia', PASSWORD_HASH('Colegio123.', 'SHA256'), '987654324', '1992-09-10', 'F', 2),
('csanchez', 'Carlos', 'Sanchez', PASSWORD_HASH('Colegio123.', 'SHA256'), '987654325', '1985-11-25', 'M', 2),
('lluna', 'Luis', 'Luna', PASSWORD_HASH('Colegio123.', 'SHA256'), '987654326', '1993-12-05', 'M', 2),
('jfernandez', 'Julia', 'Fernandez', PASSWORD_HASH('Colegio123.', 'SHA256'), '987654327', '1996-07-22', 'F', 2),
('mcastro', 'Mario', 'Castro', PASSWORD_HASH('Colegio123.', 'SHA256'), '987654328', '1992-10-11', 'M', 2),
('srodriguez', 'Sara', 'Rodriguez', PASSWORD_HASH('Colegio123.', 'SHA256'), '987654329', '1994-03-17', 'F', 2),
('jgarcia', 'Juan', 'Garcia', PASSWORD_HASH('Colegio123.', 'SHA256'), '987654330', '1991-05-30', 'M', 2),
('vhernandez', 'Veronica', 'Hernandez', PASSWORD_HASH('Colegio123.', 'SHA256'), '987654331', '1989-08-15', 'F', 2);