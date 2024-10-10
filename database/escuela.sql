create database bd_escuela;
use bd_escuela;
create table tbl_usuario(
    id_usuario int auto_increment primary key not null,
    usuario_escuela  varchar(50) not null,
    nom_usuario varchar(30) not null,
    ape_usuario varchar(30) not null,
    contra_usuario varchar(25) not null,
    telefono_usuario char(9) not null,
    fecha_nacimi_usuario date not null,
    sexo_usuario  enum('M','F') null,
    tipo_usuario  enum('educador','secretario','profesor') not null

);
insert into tbl_usuario (usuario_escuela, nom_usuario, ape_usuario, contra_usuario, telefono_usuario, fecha_nacimi_usuario, sexo_usuario, tipo_usuario) 
values 
('jperez', 'Juan', 'Perez', '123456', '987654321', '1990-01-01', 'M', 'educador'),
('mrodriguez', 'Maria', 'Rodriguez', 'qwerty', '987654322', '1995-06-15', 'F', 'secretario'),
('plopez', 'Pedro', 'Lopez', 'asdfgh', '987654323', '1980-03-20', 'M', 'profesor'),
('agarcia', 'Ana', 'Garcia', 'zxcvbn', '987654324', '1992-09-10', 'F', 'educador'),
('csanchez', 'Carlos', 'Sanchez', '1234567', '987654325', '1985-11-25', 'M', 'secretario');
