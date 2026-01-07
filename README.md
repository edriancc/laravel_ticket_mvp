# Laravel Ticket Management System (MVP)

Un sistema de gestión de tickets y tareas potente y visual, construido con **Laravel 11**, **Filament v3** y **Tailwind CSS**.

## ✨ Características Principales

- **Tablero Kanban Dinámico:** Arrastra y suelta tickets entre estados (To Do, In Progress, Done).
- **Gestión de Proyectos:** Organización de tickets por proyectos con filtros inteligentes.
- **Control de Tickets:** Prioridades (Baja, Media, Alta, Crítica), fechas de vencimiento y tipos de ticket (Bug, Feature, etc.).
- **Editor Enriquecido:** Descripciones y comentarios con soporte para imágenes y texto con formato.
- **Historial de Actividad:** Registro automático de quién cambió qué y cuándo (Audit Log).
- **Dashboard Visual:** Gráficos de distribución de tickets y usuarios para un control gerencial rápido.
- **Roles y Permisos:** Control de acceso basado en roles (Admin, Manager, User) mediante Filament Shield.

## 🚀 Requisitos del Sistema

- **PHP** >= 8.2
- **Composer**
- **Node.js & NPM**
- **Base de Datos:** MySQL, PostgreSQL o SQLite.

## 🛠️ Instalación

Sigue estos pasos para poner en marcha el proyecto localmente:

1. **Clonar el repositorio:**
   ```bash
   git clone <url-del-repositorio>
   cd laravel_ticket_mvp
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Instalar dependencias de Frontend:**
   ```bash
   npm install
   npm run build
   ```

4. **Configurar el archivo de entorno:**
   ```bash
   cp .env.example .env
   ```
   *Nota: No olvides configurar el nombre de tu base de datos y credenciales en el archivo `.env`.*

5. **Generar la clave de la aplicación:**
   ```bash
   php artisan key:generate
   ```

6. **Ejecutar migraciones y seeders:**
   Este paso creará las tablas y el usuario administrador inicial.
   ```bash
   php artisan migrate --seed
   ```

7. **Crear el enlace simbólico para imágenes:**
   ```bash
   php artisan storage:link
   ```

8. **Iniciar el servidor:**
   ```bash
   php artisan serve
   ```

## 🔐 Acceso al Sistema

Una vez iniciado el servidor, puedes acceder al panel administrativo en: `http://127.0.0.1:8000/admin`

**Credenciales iniciales:**
- **Usuario:** `admin@admin.com`
- **Contraseña:** `password`

## 🛠️ Comandos Útiles (Opcional)

Si necesitas regenerar los permisos de Shield:
```bash
php artisan shield:install
```

---
Desarrollado con ❤️ para una gestión de proyectos eficiente.
