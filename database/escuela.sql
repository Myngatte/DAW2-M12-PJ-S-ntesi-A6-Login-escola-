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
    rol_user  INT not null,
    foto_usuario VARCHAR(255) NULL
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

create table tbl_materia(
    id_materia int auto_increment primary key not null,
    nombre_materia varchar(100)
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


INSERT INTO tbl_materia (nombre_materia)
VALUES 
('Conocimiento del medio'),
('Mecánica orientada a potasio'),
('Ciencia Política y redes'),
('Veterinaria y podología animal'),
('Literatura lingüistica contemporánea'),
('História comunista y programación'),
('Ciencias del deporte occidental'),
('Metodologias de plagio avanzadas');

INSERT INTO tbl_usuario (usuario_escuela, nom_usuario, ape_usuario, contra_usuario, telefono_usuario, fecha_nacimi_usuario, sexo_usuario, rol_user, foto_usuario) 
VALUES 
('mzhou', 'Ming', 'Zhou', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654321', '2004-10-30', 'M', 2, 'mzhou.png'),
('rnoble', 'Roberto', 'Noble', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654322', '2005-06-18', 'M', 2, 'rnoble.png'),
('aorozco', 'Aina', 'Orozco', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654323', '1980-03-20', 'F', 2, 'aorozco.png'),
('mpalamari', 'Mario', 'Palamari', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654324', '1992-09-10', 'M', 2, 'mpalamari.png'),
('qevot', 'Quet', 'Evot', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654325', '1985-11-25', 'M', 2, 'qevot.png'),
('epote', 'Etxa', 'Pote', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654326', '1993-12-05', 'M', 2, 'epote.png'),
('jalberto', 'Juan', 'Alberto', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654327', '1996-07-22', 'M', 2, 'jalberto.png'),
('lshow', 'Lmd', 'Show', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654328', '1992-10-11', 'M', 2, 'lshow.png'),
('mbros', 'Mario', 'Bros', '$2a$12$gpoFd3AGKLQr0wzvwLgrSeYNZYw2r4VzWOF9TZyNfyadK83JjsLVO', '987654328', '1992-10-11', 'M', 1, 'mbros.png');
-- La contra es Colegio123.
-- Contraseña del admin qweQWE123