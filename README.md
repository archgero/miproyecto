# Registro de Equipos — Centro de Cómputos

Aplicación web sencilla en **PHP + MariaDB** para registrar los equipos de un centro de cómputos
(Práctico Final — Administración de Sistemas, SYSADM 2026).

Permite **registrar, ver, modificar y eliminar** equipos (CRUD).

## Archivos

| Archivo | Qué hace |
|---|---|
| `index.php` | Página principal: formulario de registro + tabla de equipos |
| `editar.php` | Modifica un equipo |
| `eliminar.php` | Elimina un equipo |
| `conexion.php` | Conexión a la base de datos |
| `config.example.php` | Plantilla de configuración (sin contraseñas) |
| `database.sql` | Crea la base de datos `centro_computos` y la tabla `equipos` |
| `estilos.css` | Estilos |

## Instalación en el servidor

```bash
git clone https://github.com/USUARIO/miproyecto.git /var/www/miproyecto
cd /var/www/miproyecto
cp config.example.php config.php   # y poner la contraseña real en config.php
sudo mariadb < database.sql
```

> `config.php` está en `.gitignore`: las credenciales **no** se publican en GitHub.

Disponible en: **http://miproyecto.com**
