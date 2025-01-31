# Gestión de Tareas - API en PHP + MySQL + JWT

Este proyecto es una API en PHP para gestionar tareas con autenticación mediante JWT. Permite a los usuarios registrarse, iniciar sesión y administrar sus tareas mediante un CRUD.

## 📌 Tecnologías Utilizadas
- PHP 8+
- Composer
- MySQL
- Firebase PHP JWT (`firebase/php-jwt`)
- Dotenv (`vlucas/phpdotenv`)
- Apache (XAMPP)

## 📂 Estructura del Proyecto
```
/project-root
  /app
    /Controllers
    /Models
    /Views
  /config
  /public
  /vendor
  /routes
  /tests
```
- **`app/Controllers`** → Controladores que manejan la lógica de negocio.
- **`app/Models`** → Modelos que gestionan la base de datos.
- **`routes/web.php`** → Definición de rutas.
- **`config/database.php`** → Configuración de la base de datos.
- **`public/index.php`** → Punto de entrada de la API.
- **`.env`** → Variables de entorno.

## 📦 Instalación y Configuración

### 1️⃣ Clonar el repositorio
```bash
git clone https://github.com/tu_usuario/gestion-tareas.git
cd gestion-tareas
```

### 2️⃣ Instalar dependencias con Composer
```bash
composer install
```

### 3️⃣ Configurar la base de datos
- Crear una base de datos en MySQL:
```sql
CREATE DATABASE gestion_tareas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
- Configurar el archivo `.env` en la raíz del proyecto:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_tareas
DB_USERNAME=root
DB_PASSWORD=
JWT_SECRET=tu_clave_secreta
```

### 4️⃣ Ejecutar las migraciones manualmente
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100),
    lastname VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(200),
    description TEXT,
    due_date DATE,
    status ENUM('pendiente', 'en progreso', 'completada') DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 5️⃣ Configurar Apache con VirtualHost
En `C:\xampp\apache\conf\extra\httpd-vhosts.conf`, agregar:
```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/gestion-tareas/public"
    ServerName gestion-tareas.local
    <Directory "C:/xampp/htdocs/gestion-tareas/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
Luego, en `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1 gestion-tareas.local
```

### 6️⃣ Reiniciar Apache y probar que la API está funcionando
```bash
http://gestion-tareas.local/
```

## 🚀 Endpoints de la API

### 🏠 **Autenticación**
- **Registro:**  
  - `POST /register`
  - **Body JSON:**
    ```json
    {
        "firstname": "John",
        "lastname": "Doe",
        "email": "john.doe@example.com",
        "password": "password123"
    }
    ```
- **Inicio de sesión:**  
  - `POST /login`
  - **Body JSON:**
    ```json
    {
        "email": "john.doe@example.com",
        "password": "password123"
    }
    ```
  - **Respuesta:**
    ```json
    {
        "token": "eyJhbGciOiJIUzI1Ni..."
    }
    ```

### 📋 **Gestión de Tareas (CRUD)**
- **Obtener todas las tareas** (requiere token JWT):
  - `GET /tasks`
  - **Headers:**
    ```
    Authorization: Bearer [TOKEN]
    ```

- **Crear una tarea**
  - `POST /tasks`
  - **Body JSON:**
    ```json
    {
        "title": "Nueva tarea",
        "description": "Tarea de prueba",
        "due_date": "2025-02-15",
        "status": "pendiente"
    }
    ```

- **Obtener una tarea por ID**
  - `GET /tasks/{id}`

- **Actualizar una tarea**
  - `PUT /tasks/{id}`
  - **Body JSON:**
    ```json
    {
        "title": "Tarea actualizada",
        "description": "Nueva descripción",
        "due_date": "2025-03-01",
        "status": "en progreso"
    }
    ```

- **Eliminar una tarea**
  - `DELETE /tasks/{id}`

## 🛠 **Seguridad Implementada**
- **Autenticación con JWT** (`firebase/php-jwt`).
- **Hash de contraseñas con `password_hash()`**.
- **Protección contra SQL Injection** con consultas preparadas en PDO.
- **Validación de datos en las solicitudes**.

## 📝 **Mejoras Futuras**
- Implementar refresh tokens.
- Agregar paginación en `/tasks`.
- Implementar Soft Delete en tareas.

## 📜 **Licencia**
Este proyecto está bajo la licencia MIT.
```
