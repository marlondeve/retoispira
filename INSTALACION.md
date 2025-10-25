# Guía de Instalación Paso a Paso

## Prerrequisitos

Antes de comenzar, asegúrate de tener instalado:
- XAMPP, WAMP, o similar (Apache + MySQL + PHP)
- PHP 7.4 o superior
- MySQL 5.7 o superior

## Instalación

### Paso 1: Preparar el entorno
1. Inicia tu servidor local (XAMPP/WAMP)
2. Ve a la carpeta `htdocs` (XAMPP) o `www` (WAMP)
3. Coloca el proyecto en una carpeta llamada `trellodev`

### Paso 2: Configurar la base de datos
1. Abre phpMyAdmin: `http://localhost/phpmyadmin`
2. Crea una nueva base de datos llamada `trellodev`
3. Ve a la pestaña "Importar"
4. Selecciona el archivo `database` del proyecto
5. Haz clic en "Continuar"

### Paso 3: Configurar conexión
1. Abre el archivo `conexion.php`
2. Actualiza las credenciales:
```php
$host = 'localhost';
$user = 'root';  // Usuario por defecto de XAMPP
$pass = '';      // Contraseña vacía por defecto
$dbname = 'trellodev';
```

### Paso 4: Inicializar datos
1. Abre en tu navegador: `http://localhost/trellodev/inicializar_bd.php`
2. Deberías ver: "Usuario administrador creado con éxito".

### Paso 5: Crear usuario administrador
1. Abre en tu navegador: `http://localhost/trellodev/crear_admin.php`
2. Deberías ver: "Usuario admin creado correctamente"
3. **Anota las credenciales:**
   - Email: `admin@retoinspira.com`
   - Contraseña: `Platino5..`
4. Añade las imagenes necesarias.

### Paso 6: Crear usuarios miembros
1. Accede al sistema: `http://localhost/trellodev/dashboard.php`
2. Inicia sesión con las credenciales del admin
3. Ve a la sección "Usuarios"
4. Crea al menos 2 usuarios con rol "miembro"



## 🔧 Verificación de Instalación

### Checklist de verificación:
- [ ] Base de datos creada y conectada
- [ ] Datos iniciales insertados
- [ ] Usuario admin creado
- [ ] Usuarios miembros creados


### Problemas comunes y soluciones:

#### Error: "Error de conexión a la base de datos"
**Solución:**
- Verifica que MySQL esté ejecutándose
- Revisa las credenciales en `conexion.php`
- Asegúrate de que la base de datos existe

#### Error: "No hay usuarios disponibles"
**Solución:**
- Crea usuarios con rol "miembro" en la sección Usuarios
- Asegúrate de que estén marcados como "activos"



## Acceso desde otros dispositivos

Si quieres acceder desde otros dispositivos en tu red local:

1. Encuentra tu IP local: `ipconfig` (Windows) o `ifconfig` (Mac/Linux)
2. Accede usando: `http://TU_IP_LOCAL/trellodev/dashboard.php`

## Seguridad

### Después de la instalación:
1. **Cambia la contraseña del admin** en la sección Usuarios
2. **Elimina los archivos de instalación:**
   - `crear_admin.php`
   - `inicializar_bd.php`
3. **Configura HTTPS** si es para producción

## Soporte

Si encuentras problemas:
1. Revisa los logs de error de PHP
2. Verifica la consola del navegador
3. Asegúrate de que todas las extensiones PHP estén habilitadas

---

**¡Listo! Tu sistema Reto Inspira está funcionando.**