# Sistema de Control de Donaciones - Biblioteca (Pareja 5)

## Arquitectura del Sistema

El sistema simula una arquitectura de microservicios simplificada compuesta por:

1.  **Capa Web (Frontend - PHP)**: Interfaz de usuario para la gestión de donaciones (CRUD) y visualización de reportes.
2.  **Capa Lógica (Backend - Python)**: Microservicio encargado de procesar datos de la DB y generar reportes históricos.
3.  **Capa de Datos (MySQL)**: Base de datos relacional con persistencia garantizada mediante volúmenes.

##  Tecnologías Utilizadas

*   **Docker & Docker Compose**: Orquestación de contenedores.
*   **PHP 8.0**: Lógica del servidor web y manejo de PDO para seguridad.
*   **Python 3.9**: Scripting para procesamiento de datos.
*   **MySQL 8.0**: Almacenamiento persistente.
*   **CSS Vanilla**: Diseño moderno con tema oscuro y responsivo.

##  Requisitos Previos

*   [Docker Desktop](https://www.docker.com/products/docker-desktop) instalado y en ejecución.
*   Git instalado.

## Instalación y Despliegue

1.  Clona este repositorio:
    ```bash
    git clone <url-del-repositorio>
    cd "proyecto de lenguajes pareja 5"
    ```

2.  Levanta los servicios con Docker Compose:
    ```bash
    docker-compose up --build -d
    ```

3.  Accede a la aplicación:
    [http://localhost:8080](http://localhost:8080)

##  Estructura del Proyecto

*   `src/web/`: Código fuente de la interfaz PHP y Dockerfile web.
*   `src/logic/`: Script de Python para reportes y Dockerfile de lógica.
*   `src/db/`: Script SQL inicial para la base de datos.
*   `docs/`: Documentación del proyecto, requerimientos y bitácora.

##  Seguridad y Buenas Prácticas

*   **Sentencias Preparadas**: Se utiliza PDO en PHP para prevenir inyecciones SQL.
*   **Volúmenes Compartidos**: Intercambio de archivos entre contenedores de forma segura.
*   **Conventional Commits**: Historial de versiones organizado y descriptivo.

---
**Autores:** Samuel Rojas y Jhonnmmer Maita
