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

INSERT INTO tbl_usuario (usuario_escuela, nom_usuario, ape_usuario, contra_usuario, telefono_usuario, fecha_nacimi_usuario, sexo_usuario, rol_user) 
VALUES 
('mzhou', 'Ming', 'Zhou', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654321', '2004-10-30', 'M', 2),
('rnoble', 'Roberto', 'Noble', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654322', '2005-06-18', 'M', 2),
('aorozco', 'Aina', 'Orozco', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654323', '1980-03-20', 'F', 2),
('mpalamari', 'Mario', 'Palamari', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654324', '1992-09-10', 'M', 2),
('qevot', 'Quet', 'Evot', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654325', '1985-11-25', 'M', 2),
('epote', 'Etxa', 'Pote', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654326', '1993-12-05', 'M', 2),
('jalberto', 'Juan', 'Alberto', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654327', '1996-07-22', 'M', 2),
('lshow', 'Lmd', 'Show', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654328', '1992-10-11', 'M', 2),
('mbros', 'Mario', 'Bros', '$2a$12$gpoFd3AGKLQr0wzvwLgrSeYNZYw2r4VzWOF9TZyNfyadK83JjsLVO', '987654328', '1992-10-11', 'M', 1);
-- La contra es Colegio123.
-- Contraseña del admin qweQWE123

INSERT INTO tbl_notas (id_materia, id_user, nota)
VALUES 
    (1, 2, 7.5),  
    (2, 2, 8.9),  
    (3, 2, 7.0),  
    (4, 2, 9.3),  
    (5, 2, 8.2),  
    (6, 2, 7.6),  
    (7, 2, 8.0),  
    (8, 2, 7.9),  
    (9, 2, 9.1),  
    (10, 2, 8.8),  
    (1, 3, 9.0),  
    (2, 3, 7.2),  
    (3, 3, 8.3),  
    (4, 3, 6.8),  
    (5, 3, 8.1),  
    (6, 3, 8.5),  
    (7, 3, 7.4),  
    (8, 3, 6.9),  
    (9, 3, 7.2),  
    (10, 3, 8.0),  
    (1, 4, 7.1),  
    (2, 4, 8.6),  
    (3, 4, 9.2),  
    (4, 4, 8.0),  
    (5, 4, 7.7),  
    (6, 4, 6.9),  
    (7, 4, 7.5),  
    (8, 4, 9.0),  
    (9, 4, 8.3),  
    (10, 4, 7.8),  
    (1, 5, 6.9),  
    (2, 5, 7.8),  
    (3, 5, 8.4),  
    (4, 5, 7.2),  
    (5, 5, 9.1),  
    (6, 5, 6.7),  
    (7, 5, 7.8),  
    (8, 5, 7.4),  
    (9, 5, 9.0),  
    (10, 5, 8.5),  
    (1, 6, 8.1),  
    (2, 6, 9.0),  
    (3, 6, 7.3),  
    (4, 6, 8.2),  
    (5, 6, 7.5),  
    (6, 6, 8.0),  
    (7, 6, 9.0),  
    (8, 6, 6.9),  
    (9, 6, 8.6),  
    (10, 6, 7.7),  
    (1, 7, 8.9),  
    (2, 7, 8.1),  
    (3, 7, 7.6),  
    (4, 7, 9.4),  
    (5, 7, 7.9),  
    (6, 7, 8.2),  
    (7, 7, 9.1),  
    (8, 7, 8.8),  
    (9, 7, 9.0),  
    (10, 7, 7.3),  
    (1, 8, 8.3),  
    (2, 8, 7.7),  
    (3, 8, 8.8),  
    (4, 8, 8.4),  
    (5, 8, 7.0),  
    (6, 8, 7.9),  
    (7, 8, 6.5),  
    (8, 8, 9.2),  
    (9, 8, 8.4),  
    (10, 8, 9.0);
