# Reto Inspira Web

## Descripción
Este proyecto es una aplicación web de gamificación para deportista. Ha sido desarrollada en PHP que gestiona participantes, eventos y finanzas. Ha sido diseñada para ser una herramienta de organización y seguimiento, con funcionalidades de gestión de usuarios y un panel de control interactivo.

## Características
- Gestión de usuarios (registro, inicio de sesión, administración).
- Gestión de participantes.
- Gestión de eventos.
- Seguimiento de finanzas.
- Panel de control (Dashboard) para una visión general.
- API para participantes públicos.

## Instalación
Para instalar y configurar el proyecto, por favor, consulta el archivo `INSTALACION.md` para obtener instrucciones detalladas sobre los requisitos previos, la configuración de la base de datos y los pasos de instalación.

## Uso
Una vez instalado, puedes acceder a la aplicación a través de tu navegador web. Inicia sesión con tus credenciales de administrador para acceder al panel de control y gestionar las diferentes secciones de la aplicación.

## Estructura del Proyecto
- `index.php`: Página principal de la aplicación.
- `dashboard.php`: Panel de control principal.
- `login.php`, `logout.php`, `crear_admin.php`, `procesar_usuario.php`: Archivos relacionados con la gestión de usuarios.
- `participantes.json`, `procesar_participantes.php`, `api_participantes_publico.php`, `vistas/participantes.php`: Archivos para la gestión de participantes.
- `crear_tabla_eventos.php`, `procesar_evento.php`, `insertar_eventos_prueba.php`: Archivos para la gestión de eventos.
- `finanzas.json`, `procesar_finanzas.php`, `vistas/finanzas.php`: Archivos para la gestión financiera.
- `conexion.php`, `database/`, `inicializar_bd.php`: Archivos relacionados con la base de datos.
- `LOGOTIPO.png`, `foto-grupo.jpg`, `logo.png`: Archivos de imágenes.
- `INSTALACION.md`: Documento con instrucciones de instalación detalladas.

## Tecnologías Utilizadas
- PHP
- MySQL (o MariaDB)
- HTML5
- CSS (Tailwind CSS)
- JavaScript