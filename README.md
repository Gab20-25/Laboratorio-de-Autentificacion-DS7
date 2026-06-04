# Laboratorio - Autenticacion con 2FA

## Introduccion

En este laboratorio se implemento un sistema de autenticacion de dos factores (2FA)
utilizando PHP, MySQL y Google Authenticator.

El objetivo principal fue agregar una capa adicional de seguridad al proceso de login
mediante codigos TOTP (Time-Based One-Time Password), complementado con buenas practicas
de seguridad como proteccion CSRF, sanitizacion de datos y hashing de contrasenas.

---

## Objetivo del Laboratorio

- Implementar autenticacion de dos factores (2FA) con Google Authenticator.
- Crear usuarios de base de datos con privilegios minimos.
- Sanitizar y validar datos de entrada del usuario.
- Proteger formularios contra ataques CSRF.
- Aplicar hashing seguro de contrasenas con password_hash().
- Registrar intentos de login en una tabla de auditoria.

---

## Tecnologias Utilizadas

- PHP
- MySQL (WAMP)
- Composer
- sonata-project/google-authenticator
- HTML/CSS
- JavaScript

---

## Estructura del Proyecto

```
Laboratorio Autentificacion/
|
|-- clases/
|   |-- myConexionPDO.php
|   |-- CSRFProteccion.php
|   |-- SanitizarEntrada.php
|   |-- RegistroUsuario.php
|
|-- vendor/
|-- composer.json
|-- composer.lock
|-- registro.php
|-- procesarRegistro.php
|-- login.php
|-- procesarLogin.php
|-- mostrarQR.php
|-- verificar2fa.php
|-- dashboard.php
|-- salir.php
|-- crearHash.php
|-- verificarDuplicado.php
```

---

## Base de Datos

### Creacion de la base de datos y tablas

```sql
CREATE DATABASE company_info 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE company_info;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Apellido VARCHAR(100) NOT NULL,
    Usuario VARCHAR(100) NOT NULL UNIQUE,
    Correo VARCHAR(150) NOT NULL UNIQUE,
    HashMagic VARCHAR(255) NOT NULL,
    Sexo CHAR(1) NOT NULL,
    secret_2fa VARCHAR(255) NULL,
    FechaSistema DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE intentos_login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Usuario VARCHAR(150) NOT NULL,
    ipRemoto VARCHAR(50),
    Estado VARCHAR(10) NOT NULL,
    FechaSistema DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### Usuario con privilegios minimos

```sql
CREATE USER 'lab2fa_user'@'localhost' IDENTIFIED BY 'Lab2FA_2026!';
GRANT SELECT, INSERT, UPDATE ON company_info.* TO 'lab2fa_user'@'localhost';
FLUSH PRIVILEGES;
```

### Resultado del SHOW GRANTS
+-------------------------------------------------------------------------------+
| Grants for lab2fa_user@localhost                                              |
+-------------------------------------------------------------------------------+
| GRANT USAGE ON . TO lab2fa_user@localhost                                   |
| GRANT SELECT, INSERT, UPDATE ON company_info.* TO lab2fa_user@localhost       |
+-------------------------------------------------------------------------------+

---

## Instalacion y Configuracion

1. Clonar el repositorio dentro de la carpeta `www` de WAMP:

git clone https://github.com/Gab20-25/Laboratorio-de-Autentificacion-DS7.git

2. Instalar las dependencias con Composer:

cd Laboratorio-de-Autentificacion-DS7
composer install

3. Si la librería de autenticacion no se instala automaticamente, ejecutar:

composer require sonata-project/google-authenticator

4. Crear la base de datos ejecutando el SQL de la seccion anterior.

5. Ajustar las credenciales en `clases/myConexionPDO.php` si es necesario.

6. Acceder desde el navegador:

http://127.0.0.1/Laboratorio-de-Autentificacion-DS7/registro.php

---

## Flujo del Sistema

1. El usuario se registra con sus datos personales.
2. Al registrarse, se genera un secreto 2FA unico y se muestra como codigo QR.
3. El usuario escanea el QR con una app de autenticacion (Google Authenticator, Microsoft Authenticator).
4. Al hacer login ingresa su usuario y contrasena.
5. Si son correctos, se solicita el codigo temporal de 6 digitos.
6. Si el codigo es valido, se concede acceso completo al sistema.
7. Cada intento de login (exitoso o fallido) queda registrado en la tabla intentos_login.

---

## Dificultades y Soluciones

- Problema: Al registrar un usuario con un correo o nombre de usuario ya existente, el sistema lanzaba un error de base de datos en lugar de mostrar un mensaje amigable al usuario.
- Solucion: Se implemento una verificacion en tiempo real con JavaScript que consulta el archivo verificarDuplicado.php antes de enviar el formulario, informando al usuario si el correo o nombre de usuario ya esta en uso.

---

## Conclusiones

La implementación de este laboratorio permitió comprender de forma práctica cómo funciona un sistema de autenticación seguro en PHP. La incorporación del 2FA con Google Authenticator agrega una capa crítica de seguridad que va más allá de la simple validación de usuario y contraseña, protegiendo el acceso incluso si las credenciales son comprometidas.

El uso de tokens CSRF, sanitización de entradas y hashing con bcrypt demostró que la seguridad no es una característica opcional sino una responsabilidad que debe estar presente desde el diseño inicial de cualquier aplicación web.

---

## Referencias

- Documentacion oficial de Composer: https://getcomposer.org/doc/
- Guias de laboratorio proporcionadas por la profesora
- Repositorio base: https://github.com/Salomon2514/EjemploBaseLogin

---

## Informacion de Estudiantes

- **Integrantes: Gabriel Ah Chu, Idianeth Hanna**
- **Correos: gabriel.ahchu@utp.ac.pa, idianeth.hanna@utp.ac.pa**
- **Curso:** Desarrollo de Software 7
- **Instructor:** Irina Fong
- **Fecha de Entrega:** 4 de junio de 2026
