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
    nombre_materia varchar(20)
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
('Biologia y Geologia'),
('Educacion Fisica'),
('Educacion Plastica, Visual y Audiovisual'),
('Fisica y Quimica'),
('Geografia e Historia'),
('Lengua Castellana y Literatura y, si la hubiere, Lengua Cooficial y Literatura'),
('Lengua Extranjera'),
('Matematicas'),
('Musica'),
('Tecnologia y Digitalizacion');

INSERT INTO tbl_usuario (usuario_escuela, nom_usuario, ape_usuario, contra_usuario, telefono_usuario, fecha_nacimi_usuario, sexo_usuario, rol_user) 
VALUES 
('jperez', 'Juan', 'Perez', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654321', '1990-01-01', 'M', 1),
('mrodriguez', 'Maria', 'Rodriguez', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654322', '1995-06-15', 'F', 2),
('plopez', 'Pedro', 'Lopez', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654323', '1980-03-20', 'M', 2),
('agarcia', 'Ana', 'Garcia', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654324', '1992-09-10', 'F', 2),
('csanchez', 'Carlos', 'Sanchez', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654325', '1985-11-25', 'M', 2),
('lluna', 'Luis', 'Luna', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654326', '1993-12-05', 'M', 2),
('jfernandez', 'Julia', 'Fernandez', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654327', '1996-07-22', 'F', 2),
('mcastro', 'Mario', 'Castro', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654328', '1992-10-11', 'M', 2),
('srodriguez', 'Sara', 'Rodriguez', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654329', '1994-03-17', 'F', 2),
('jgarcia', 'Juan', 'Garcia', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654330', '1991-05-30', 'M', 2),
('vhernandez', 'Veronica', 'Hernandez', '$2y$10$AxRGEcFkhfCapF2LJvMf8uIlWxbm8YldHJMM82tHROdI5duErL46a', '987654331', '1989-08-15', 'F', 2);


INSERT INTO tbl_notas (id_materia, id_user, nota)
VALUES 
    (1, 2, 7.5),  -- Biologia y Geologia for Maria Rodriguez
    (2, 2, 8.9),  -- Educacion Fisica for Maria Rodriguez
    (3, 2, 7.0),  -- Educacion Plastica, Visual y Audiovisual for Maria Rodriguez
    (4, 2, 9.3),  -- Fisica y Quimica for Maria Rodriguez
    (5, 2, 8.2),  -- Geografia e Historia for Maria Rodriguez
    (6, 2, 7.6),  -- Lengua Castellana y Literatura for Maria Rodriguez
    (7, 2, 8.0),  -- Lengua Extranjera for Maria Rodriguez
    (8, 2, 7.9),  -- Matematicas for Maria Rodriguez
    (9, 2, 9.1),  -- Musica for Maria Rodriguez
    (10, 2, 8.8),  -- Tecnologia y Digitalizacion for Maria Rodriguez

    (1, 3, 9.0),  -- Biologia y Geologia for Pedro Lopez
    (2, 3, 7.2),  -- Educacion Fisica for Pedro Lopez
    (3, 3, 8.3),  -- Educacion Plastica, Visual y Audiovisual for Pedro Lopez
    (4, 3, 6.8),  -- Fisica y Quimica for Pedro Lopez
    (5, 3, 8.1),  -- Geografia e Historia for Pedro Lopez
    (6, 3, 8.5),  -- Lengua Castellana y Literatura for Pedro Lopez
    (7, 3, 7.4),  -- Lengua Extranjera for Pedro Lopez
    (8, 3, 6.9),  -- Matematicas for Pedro Lopez
    (9, 3, 7.2),  -- Musica for Pedro Lopez
    (10, 3, 8.0),  -- Tecnologia y Digitalizacion for Pedro Lopez

    (1, 4, 7.1),  -- Biologia y Geologia for Ana Garcia
    (2, 4, 8.6),  -- Educacion Fisica for Ana Garcia
    (3, 4, 9.2),  -- Educacion Plastica, Visual y Audiovisual for Ana Garcia
    (4, 4, 8.0),  -- Fisica y Quimica for Ana Garcia
    (5, 4, 7.7),  -- Geografia e Historia for Ana Garcia
    (6, 4, 6.9),  -- Lengua Castellana y Literatura for Ana Garcia
    (7, 4, 7.5),  -- Lengua Extranjera for Ana Garcia
    (8, 4, 9.0),  -- Matematicas for Ana Garcia
    (9, 4, 8.3),  -- Musica for Ana Garcia
    (10, 4, 7.8),  -- Tecnologia y Digitalizacion for Ana Garcia

    (1, 5, 6.9),  -- Biologia y Geologia for Carlos Sanchez
    (2, 5, 7.8),  -- Educacion Fisica for Carlos Sanchez
    (3, 5, 8.4),  -- Educacion Plastica, Visual y Audiovisual for Carlos Sanchez
    (4, 5, 7.2),  -- Fisica y Quimica for Carlos Sanchez
    (5, 5, 9.1),  -- Geografia e Historia for Carlos Sanchez
    (6, 5, 6.7),  -- Lengua Castellana y Literatura for Carlos Sanchez
    (7, 5, 7.8),  -- Lengua Extranjera for Carlos Sanchez
    (8, 5, 7.4),  -- Matematicas for Carlos Sanchez
    (9, 5, 9.0),  -- Musica for Carlos Sanchez
    (10, 5, 8.5),  -- Tecnologia y Digitalizacion for Carlos Sanchez

    (1, 6, 8.1),  -- Biologia y Geologia for Luis Luna
    (2, 6, 9.0),  -- Educacion Fisica for Luis Luna
    (3, 6, 7.3),  -- Educacion Plastica, Visual y Audiovisual for Luis Luna
    (4, 6, 8.2),  -- Fisica y Quimica for Luis Luna
    (5, 6, 7.5),  -- Geografia e Historia for Luis Luna
    (6, 6, 8.0),  -- Lengua Castellana y Literatura for Luis Luna
    (7, 6, 9.0),  -- Lengua Extranjera for Luis Luna
    (8, 6, 6.9),  -- Matematicas for Luis Luna
    (9, 6, 8.6),  -- Musica for Luis Luna
    (10, 6, 7.7),  -- Tecnologia y Digitalizacion for Luis Luna

    (1, 7, 8.9),  -- Biologia y Geologia for Julia Fernandez
    (2, 7, 8.1),  -- Educacion Fisica for Julia Fernandez
    (3, 7, 7.6),  -- Educacion Plastica, Visual y Audiovisual for Julia Fernandez
    (4, 7, 9.4),  -- Fisica y Quimica for Julia Fernandez
    (5, 7, 7.9),  -- Geografia e Historia for Julia Fernandez
    (6, 7, 8.2),  -- Lengua Castellana y Literatura for Julia Fernandez
    (7, 7, 9.1),  -- Lengua Extranjera for Julia Fernandez
    (8, 7, 8.8),  -- Matematicas for Julia Fernandez
    (9, 7, 9.0),  -- Musica for Julia Fernandez
    (10, 7, 7.3),  -- Tecnologia y Digitalizacion for Julia Fernandez

    (1, 8, 8.3),  -- Biologia y Geologia for Mario Castro
    (2, 8, 7.7),  -- Educacion Fisica for Mario Castro
    (3, 8, 8.8),  -- Educacion Plastica, Visual y Audiovisual for Mario Castro
    (4, 8, 8.4),  -- Fisica y Quimica for Mario Castro
    (5, 8, 7.0),  -- Geografia e Historia for Mario Castro
    (6, 8, 7.9),  -- Lengua Castellana y Literatura for Mario Castro
    (7, 8, 6.5),  -- Lengua Extranjera for Mario Castro
    (8, 8, 9.2),  -- Matematicas for Mario Castro
    (9, 8, 8.4),  -- Musica for Mario Castro
    (10, 8, 9.0);  -- Tecnologia y Digitalizacion for Mario Castro
