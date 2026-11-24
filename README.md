<div align="center">

# Fluxxo — Backend API

**Sistema Inteligente de Gestión de Turnos y Filas Virtuales**

Backend REST y en tiempo real desarrollado con Laravel 13 para la gestión multi-organización de turnos, priorización dinámica por reglas, verificación de asistencia vía QR y monitoreo operativo en vivo.

[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![Redis](https://img.shields.io/badge/Redis-Cache%20%26%20Queues-DC382D?style=for-the-badge&logo=redis&logoColor=white)](https://redis.io)
[![License](https://img.shields.io/badge/License-MIT-22C55E?style=for-the-badge)](LICENSE)

</div>

---

## 📌 Acerca del Proyecto

**Fluxxo** moderniza la atención presencial y remota eliminando las filas físicas innecesarias. Su backend ofrece una arquitectura modular preparada para múltiples sedes y organizaciones, combinando asignación inteligente de turnos, notificaciones multicanal y actualización instantánea de pantallas mediante WebSockets.

### Características Principales

- **Arquitectura Multi-Organización:** Soporte estructurado para organizaciones, dependencias (sedes/áreas) y taquillas de atención.
- **Motor de Priorización Parametrizable:** Cálculo dinámico de puntajes y posición en cola basado en reglas configurables por servicio.
- **Autenticación Diferenciada:**
  - **Staff (Administradores y Operadores):** Cédula + Correo electrónico + Contraseña + verificación OTP.
  - **Clientes:** Cédula + Número de celular + verificación por SMS OTP.
- **Check-in Físico mediante QR:** Validación de asistencia presencial con tokens únicos para reducir ausentismos.
- **Sistema de Reputación y Penalizaciones:** Seguimiento del comportamiento de asistencia de los clientes para proteger la equidad de la fila virtual.
- **Pantalla Pública en Tiempo Real:** Difusión de llamados y cambios de estado de turnos mediante **Laravel Reverb (WebSockets)** y **Redis**.
- **Dashboard Analítico y Auditoría:** Trazabilidad completa de eventos operativos, tiempos de espera, tiempos de atención y métricas de desempeño.

### Stack Tecnológico

| Capa | Tecnología |
| :--- | :--- |
| **Framework** | Laravel 13 |
| **Lenguaje** | PHP 8.3+ |
| **Base de Datos** | MySQL 8.x + Eloquent ORM |
| **Tiempo Real** | Laravel Reverb (WebSockets) |
| **Caché y Colas** | Redis |
| **Testing & Calidad** | PHPUnit 12, Laravel Pint |

---

## 🧱 Arquitectura y Módulos

El modelo de datos se compone de **14 tablas principales** organizadas en **4 bloques funcionales** independientes para facilitar el desarrollo paralelo y la escalabilidad:

| # | Bloque Funcional | Responsabilidad | Tablas |
| :-: | :--- | :--- | :--- |
| **1** | **Estructura Física** | Administración de organizaciones, sedes/dependencias y puntos físicos de atención. | `organizaciones`, `dependencias`, `taquillas` |
| **2** | **Identidad y Acceso** | Gestión de roles, personal administrativo/operativo, clientes, credenciales y reputación. | `roles`, `usuarios_staff`, `clientes` |
| **3** | **Catálogo y Reglas** | Definición de servicios, franjas horarias y reglas ponderadas de priorización. | `servicios`, `horarios_atencion`, `reglas_prioridad`, `servicio_regla_prioridad` |
| **4** | **Operación y Auditoría** | Ciclo de vida del turno, confirmaciones/check-in QR, envíos de notificaciones e historial auditable. | `turnos`, `confirmaciones_asistencia`, `notificaciones`, `registros_historicos` |

---

## ⚙️ Requisitos Previos

Puedes ejecutar el proyecto tanto en **WSL / Ubuntu** como directamente en **Windows (sin WSL)**. Asegúrate de contar con:

- **PHP** `>= 8.3` con las extensiones: `pdo_mysql`, `mbstring`, `xml`, `curl`, `zip`, `openssl`, `bcmath`, `fileinfo`
- **Composer** `>= 2.7`
- **MySQL** `>= 8.0`
- **Redis** *(para caché, colas y broadcasting con Reverb)*
- **Git** `>= 2.30`

Según tu sistema operativo:
- **WSL / Ubuntu (recomendado):** WSL 2 con Ubuntu 24.04+ y los servicios nativos de PHP y MySQL.
- **Windows (sin WSL):** **Laragon** *(recomendado)* o **XAMPP** (con PHP 8.3+ y MySQL 8+) + **Composer** + **Git**.

Puedes verificar rápidamente tu entorno con:

```bash
php -v
composer --version
mysql --version
git --version
```

---

## 🚀 Instalación y Configuración Local

Elige la guía correspondiente a tu entorno de desarrollo:

### 🐧 Opción A: WSL / Ubuntu

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/Juaanyzy0/php-fluxxo.git
   cd php-fluxxo
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Configurar las variables de entorno y generar la llave:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Verificar MySQL y crear la base de datos y usuario local:**
   ```bash
   sudo service mysql start
   ```
   Crea la base de datos `fluxxo` y un usuario dedicado en MySQL (para evitar errores con `auth_socket` de `root` en Ubuntu):
   ```sql
   CREATE DATABASE fluxxo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'fluxxo_user'@'127.0.0.1' IDENTIFIED BY 'fluxxo_pass';
   GRANT ALL PRIVILEGES ON fluxxo.* TO 'fluxxo_user'@'127.0.0.1';
   FLUSH PRIVILEGES;
   ```
   Verifica las credenciales en `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=fluxxo
   DB_USERNAME=fluxxo_user
   DB_PASSWORD=fluxxo_pass
   ```

5. **Ejecutar las migraciones:**
   ```bash
   php artisan migrate
   ```

6. **Iniciar el servidor de desarrollo:**
   ```bash
   php artisan serve
   ```
   La aplicación quedará disponible en `http://localhost:8000`.

---

### 🪟 Opción B: Windows (sin WSL)

#### Requisitos recomendados para Windows
- **Laragon** *(recomendado por su facilidad para gestionar PHP 8.3+, MySQL 8+ y Composer)* o **XAMPP** (con PHP 8.3+ y MySQL 8+).
- **Composer** instalado globalmente y disponible en el `PATH`.
- **Git** (incluye **Git Bash**; también puedes usar **PowerShell** o **CMD**).

#### Paso a paso (PowerShell / CMD / Git Bash)

1. **Clonar el repositorio y entrar a la carpeta:**
   ```bash
   git clone https://github.com/Juaanyzy0/php-fluxxo.git
   cd php-fluxxo
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Copiar el archivo de variables de entorno:**
   - En **CMD / PowerShell**:
     ```powershell
     copy .env.example .env
     ```
   - En **Git Bash**:
     ```bash
     cp .env.example .env
     ```

4. **Generar la llave de la aplicación:**
   ```bash
   php artisan key:generate
   ```

5. **Iniciar MySQL y crear la base de datos `fluxxo`:**
   - Abre el panel de **Laragon** o **XAMPP** e inicia el servicio de **MySQL**.
   - Crea una base de datos vacía llamada `fluxxo` (cotejamiento `utf8mb4_unicode_ci`) desde **phpMyAdmin** (o HeidiSQL / consola de MySQL):
     ```sql
     CREATE DATABASE fluxxo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```

6. **Configurar `.env` con tus credenciales locales de Windows:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=fluxxo
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. **Ejecutar las migraciones:**
   ```bash
   php artisan migrate
   ```

8. **Levantar el servidor local:**
   ```bash
   php artisan serve
   ```
   La aplicación quedará disponible en `http://localhost:8000`.

> [!NOTE]
> **Credenciales locales sin conflictos:** El archivo `.env` está incluido en `.gitignore`, por lo que las credenciales locales de cada integrante (ya sea en **Windows** con `root` o en **WSL/Ubuntu** con `fluxxo_user`) nunca se suben al repositorio ni chocan entre sí al hacer `git push` o `git pull`.

### Comandos útiles

```bash
# Reiniciar el esquema desde cero en desarrollo local
php artisan migrate:fresh

# Verificar la conexión activa a MySQL
php artisan db:show

# Ejecutar la suite de pruebas
php artisan test

# Formatear código siguiendo estándares PSR-12 / Laravel (Pint)
./vendor/bin/pint

# Iniciar servidor de WebSockets (una vez configurado Laravel Reverb)
php artisan reverb:start
```

---

## 🌿 Flujo de Trabajo con Git (Branching)

Para mantener la estabilidad del repositorio y evitar conflictos durante el desarrollo colaborativo, el equipo sigue un flujo basado en ramas por funcionalidad (**Feature Branching**):

> **Regla fundamental:** La rama `main` siempre debe mantenerse estable y funcional. **Nunca se realiza `push` directo a `main`.** Todo cambio debe integrarse mediante un **Pull Request (PR)** revisado y aprobado.

### Convención de ramas

Cada integrante desarrolla su bloque o tarea en una rama independiente con el prefijo `feat/` (o `fix/` / `docs/` según corresponda):

- `feat/estructura-fisica` — Bloque 1 (`organizaciones`, `dependencias`, `taquillas`)
- `feat/identidad-acceso` — Bloque 2 (`roles`, `usuarios_staff`, `clientes`)
- `feat/catalogo-reglas` — Bloque 3 (`servicios`, `horarios_atencion`, `reglas_prioridad`, `servicio_regla_prioridad`)
- `feat/operacion-auditoria` — Bloque 4 (`turnos`, `confirmaciones_asistencia`, `notificaciones`, `registros_historicos`)

### Ciclo paso a paso

1. **Actualizar `main` antes de iniciar una tarea:**
   ```bash
   git checkout main
   git pull origin main
   ```

2. **Crear una nueva rama desde `main`:**
   ```bash
   git checkout -b feat/nombre-de-tu-modulo
   ```

3. **Realizar commits claros y atómicos** siguiendo [Conventional Commits](https://www.conventionalcommits.org/):
   ```bash
   git add .
   git commit -m "feat(turnos): agrega migraciones de operacion y auditoria"
   ```
   Prefijos recomendados: `feat:`, `fix:`, `refactor:`, `docs:`, `test:`, `chore:`.

4. **Publicar la rama en el repositorio remoto:**
   ```bash
   git push -u origin feat/nombre-de-tu-modulo
   ```

5. **Abrir un Pull Request hacia `main`:**
   - Describe brevemente los cambios realizados y cómo probarlos.
   - Solicita la revisión de al menos un compañero del equipo antes de hacer *merge*.

---

## 👥 Equipo de Desarrollo

Proyecto desarrollado en el marco del programa **Tecnólogo en Análisis y Desarrollo de Software (ADSO)** — **SENA (Ficha 3478073)**.

| Integrante | Rol | Programa / Ficha |
| :--- | :--- | :--- |
| **Harrison Stiven Ballén Sánchez** | Desarrollador Backend | SENA — ADSO (3478073) |
| **Juan Camilo Aragón Mora** | Desarrollador Backend | SENA — ADSO (3478073) |
| **Manuel Alejandro Bravo de Mares** | Desarrollador Backend | SENA — ADSO (3478073) |
| **Brayan David Medina Ardila** | Desarrollador Backend | SENA — ADSO (3478073) |

---

## 📄 Licencia

Este proyecto se distribuye bajo la licencia [MIT](https://opensource.org/licenses/MIT).
