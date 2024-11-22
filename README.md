# CREACIÓN DEL LOGIN DE UNA ESCUELA

Este proyecto es un sistema de login para una escuela, desarrollado para gestionar el acceso de usuarios a un sistema protegido con autenticación. Incluye validaciones de usuario y contraseñas, encriptación de contraseñas, y protección contra inyecciones SQL. El diseño está basado en un mockup creado en Figma, y es completamente responsive tanto en la versión de escritorio como en la versión móvil.

## TECNOLOGÍAS UTILIZADAS

- **PHP**: Manejo de sesiones, validaciones del formulario y conexión con la base de datos.
- **JavaScript**: Validaciones del lado del cliente y mejora de la interacción del formulario.
- **MySQL**: Almacenamiento seguro de las credenciales de los usuarios.
- **HTML/CSS**: Estructura y diseño del formulario de login, siguiendo los lineamientos del mockup en Figma.

## CARACTERÍSTICAS PRINCIPALES

- **Responsive Design**: El diseño del formulario de login es completamente adaptable a diferentes tamaños de pantalla, tanto para dispositivos de escritorio como móviles.
- **Validación del Formulario**: Se han implementado validaciones en el lado del cliente (JavaScript) y en el servidor (PHP) para garantizar la seguridad y la integridad de los datos ingresados.
- **Protección contra Inyecciones SQL**: Todas las consultas a la base de datos están protegidas contra inyecciones SQL mediante el uso de declaraciones preparadas.
- **Encriptación de Contraseñas**: Las contraseñas de los usuarios se encriptan antes de almacenarse en la base de datos para garantizar la seguridad de la información.
- **Gestión de Sesiones**: El sistema gestiona sesiones de usuario para mantener el estado de autenticación de los usuarios.


