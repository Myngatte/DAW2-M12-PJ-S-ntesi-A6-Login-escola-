create database bd_escuela;
use bd_escuela;
create table tbl_usuario(
    id_usuario int auto_increment primary key not null,
    nom_usuario varchar(30) not null,
    contra_usuario varchar(25) not null,
    telefono_usuario char(9) not null,
    fecha_nacimi_usuario date not null,
    sexo_usuario  enum('M','F') null,
    tipo_usuario  enum('educador','secretario','profesor') not null

);