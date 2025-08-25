<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Portafolio Profesional con Blog Administrable (Laravel 12)

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)
![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red)
![Frontend](https://img.shields.io/badge/Frontend-Livewire%2C%20Blade%2C%20Bootstrap-blue)

Aplicación web que combina un portafolio profesional con un sistema de blog completamente administrable, construido con Laravel 12.

## Estructura de Datos

### Modelos Principales
- **User**: Datos personales (nombre, foto, biografía, puesto)
- **Education**: Estudios y certificaciones
- **WorkExperience**: Experiencia laboral
- **Skill**: Habilidades técnicas y profesionales
- **SocialNetwork**: Redes sociales y contactos
- **Post**: Artículos del blog con contenido y estado de publicación
- **PostCategory**: Categorías para organización temática
- **PostTag**: Etiquetas para clasificación detallada
- **PostComment**: Sistema de comentarios

## Secciones del Sitio

- **Portafolio Profesional**:
  - Perfil personal con información biográfica
  - Educación y certificaciones
  - Experiencia laboral
  - Habilidades técnicas
  - Redes sociales

- **Blog**:
  - Artículos organizados por categorías
  - Sistema de etiquetas
  - Comentarios
  - Búsqueda de contenido

## Características Principales

- **Portafolio Profesional**
  - Perfil personal con imagen y biografía
  - Timeline de educación y certificaciones
  - Cards de experiencia laboral
  - Barras de progreso para habilidades
  - Enlaces a redes sociales con iconos
  - Diseño responsive con Bootstrap 5
  - Interfaz construida con Livewire y Blade

- **Blog Administrable**
  - CRUD completo para artículos
  - Categorías y etiquetas
  - Sistema de comentarios
  - Editor WYSIWYG (CKEditor)
  - Gestión de imágenes
  - Estilos con Bootstrap 5

- **Panel de Administración**
  - Autenticación segura
  - Gestión de usuarios y roles
  - Estadísticas básicas
  - Configuración del sitio
  - Gestión de secciones del portafolio (perfil, educación, experiencia, etc.)
  - Interfaz construida con Livewire y Blade

## Requisitos Técnicos

- PHP 8.2 o superior
- Laravel 12
- Composer 2.5+
- Base de datos MySQL 8.0+ o MariaDB 10.3+
- Node.js 18+ (para assets frontend)
- npm 9+ o yarn 1.22+
- Bootstrap 5.3.7
- FontAwesome 6.7.2 (para iconos)

## Instalación y Configuración

1. Clonar el repositorio:
```bash
git clone https://github.com/MarioSandovalP3/laravel-12-portafolio-blog.git
cd laravel-12-portafolio-blog
```

2. Instalar dependencias:
```bash
composer install
npm install
```

3. Compilar y optimizar los archivos frontend (JavaScript, CSS, etc.) usando Vite:
```bash
npm run build
```

4. Para que las imágenes y archivos subidos sean accesibles públicamente, ejecuta:
```bash
php artisan storage:link
```
Esto creará un enlace simbólico desde `public/storage` a `storage/app/public`.

5. Configurar entorno:
```bash
cp .env.example .env
php artisan key:generate
```

6. Configurar base de datos en `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_bd
DB_USERNAME=usuario
DB_PASSWORD=contraseña
```

7. Migrar y poblar base de datos:
```bash
php artisan migrate --seed
```

8. Iniciar servidor de desarrollo:
```bash
php artisan serve
```

## Acceso al Panel de Administración

Para acceder al panel de administración, utiliza las siguientes credenciales:

- **URL**: `http://127.0.0.1:8000/login`
- **Usuario**: `admin@email.com`
- **Contraseña**: `admin123`

## Configuración para Producción

1. Optimizar el autoloader:
```bash
composer install --optimize-autoloader --no-dev
```

2. Cachear configuraciones y rutas:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Configuración con Docker Compose

El proyecto incluye un archivo `docker-compose.yml` preconfigurado con:

1. **Servicio MySQL**:
   - Imagen: mysql:8.0
   - Puerto: 3306
   - Credenciales:
     - Usuario root: root
     - Base de datos: laravel_db
     - Usuario app: laravel_user
     - Contraseña: secret
   - Volumen persistente para datos

2. **Servicio phpMyAdmin (opcional)**:
   - Accesible en: http://localhost:8080
   - Configurado para conectarse automáticamente al servicio MySQL

### Pasos para usar Docker:

1. Copiar el archivo `.env.example` a `.env` y configurar:
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=secret
```

2. Ejecutar los servicios:
```bash
docker-compose up -d
```

3. Para detener los servicios:
```bash
docker-compose down
```

> Nota: Los datos de MySQL persisten gracias al volumen configurado.

## Estructura de Datos

El portafolio utiliza los siguientes modelos principales:

- **User**: Datos personales (nombre, foto, biografía, puesto)
- **Education**: Estudios y certificaciones
- **WorkExperience**: Experiencia laboral
- **Skill**: Habilidades técnicas y profesionales
- **SocialNetwork**: Redes sociales y contactos
- **Post**: Artículos del blog con contenido y estado de publicación
- **PostCategory**: Categorías para organización temática
- **PostTag**: Etiquetas para clasificación detallada
- **PostComment**: Sistema de comentarios

## Configuración Adicional

Para configurar el correo (contacto y notificaciones), editar en `.env`:
```ini
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Despliegue con Docker

El proyecto incluye configuración Docker con los siguientes servicios:

- **MySQL 8.0**: Base de datos para la aplicación
- **phpMyAdmin**: Interfaz web para administrar la base de datos

### Configuración inicial:

1. Construir los contenedores:
```bash
docker-compose build
```

2. Iniciar los servicios:
```bash
docker-compose up -d
```

### Configuración de la base de datos:

- **Host**: mysql (nombre del servicio en Docker)
- **Puerto**: 3306
- **Usuario**: laravel_user
- **Contraseña**: secret
- **Base de datos**: laravel_db

### Acceso a phpMyAdmin:

1. Abrir en el navegador: http://localhost:8080
2. Credenciales:
   - Usuario: root
   - Contraseña: root

### Variables de entorno para Docker:

Configurar en `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=secret
```

## Modelo de Negocio

Este proyecto sigue un modelo **Open Core**:
- **Versión Gratuita**: Código completo disponible en GitHub (MIT License)
- **Servicios Premium**:
  - Configuración, personalización, migraciones.
  - Personalizaciones y desarrollo de módulos adicionales
  - Soporte técnico prioritario
  - Optimizaciones SEO
  - Plantillas exclusivas
  

Estructura de monetización:
```
GitHub (gratis) → Genera interés → Conversión a clientes de servicios pagos
```

## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](https://opensource.org/licenses/MIT).

## ☕ Apóyame

Si este proyecto te fue útil, puedes apoyarme con una donación:

[![ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/T6T71IEJZ2)

[![PayPal](https://img.shields.io/badge/PayPal-Donar-00457C?logo=paypal&logoColor=white)](https://paypal.me/linkvems)
