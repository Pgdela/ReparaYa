# ReparaYa - Producto 2

## Descripción

ReparaYa es una aplicación web para gestionar solicitudes de reparación
de servicios domésticos.

La aplicación permite gestionar clientes, técnicos, incidencias,
asignaciones, estados y el historial de las reparaciones.

## Tecnologías utilizadas

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- Docker
- PDO

## Estructura del proyecto

- app/controllers: controladores.
- app/helpers: funciones auxiliares.
- app/models: modelos y acceso a datos.
- app/views: vistas de la aplicación.
- config: configuración.
- database: archivos SQL.
- public: punto de entrada de la aplicación.
- routes: configuración de rutas.

## Tipos de usuario

### Administrador
- Gestionar incidencias.
- Asignar técnicos.
- Gestionar técnicos.
- Consultar el calendario.
- Consultar el historial.

### Técnico
- Consultar sus incidencias asignadas.
- Consultar los detalles.
- Actualizar el estado de las reparaciones.

### Cliente
- Registrarse e iniciar sesión.
- Solicitar reparaciones.
- Consultar sus incidencias.
- Consultar el historial.
- Cancelar solicitudes permitidas.
- Modificar su perfil.

## Base de datos

Los archivos SQL se encuentran en la carpeta database.

- bbddReparaYa.sql
- 02_ampliacion.sql

## Instalación

1. Tener Docker Desktop instalado.
2. Descargar o clonar el proyecto.
3. Configurar la conexión a la base de datos.
4. Iniciar los contenedores Docker.
5. Acceder a la aplicación desde el navegador.

## Acceso

http://localhost:8080/reparaya-producto2/public/index.php

## Seguridad

- Contraseñas protegidas mediante password_hash().
- Verificación mediante password_verify().
- Consultas preparadas con PDO.
- Control de acceso según el rol del usuario.
- Validaciones en el servidor.

## Proyecto académico

Producto 2 - Desarrollo de Aplicaciones Web.