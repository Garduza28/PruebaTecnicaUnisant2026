# SII Prueba Técnica – Laravel 12

> **Proyecto:** Sistema Integral de Información (SII) – Versión Prueba Técnica  
> **Framework:** Laravel 12  
> **PHP:** 8.2+  
> **Base de datos:** SQLite (por defecto) o MySQL  
> **Duración estimada:** 6–8 horas (máximo 1 día)  

---

## 🚀 Instalación rápida

```bash
# 1. Clonar o copiar el proyecto
cd prueba-tecnica-unisant

# 2. Instalar dependencias
composer install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Base de datos (SQLite ya está configurada)
touch database/database.sqlite
php artisan migrate --seed

# 5. Iniciar servidor
php artisan serve

> **¿`php artisan serve` no funciona?** En Windows con PHP 8.4+ puede fallar. Usa alguna de estas alternativas:
> - **Herd:** Si tienes Laravel Herd instalado, el proyecto debería aparecer automáticamente en `http://prueba-tecnica-unisant.test`.
> - **Servidor PHP nativo:** `php -S 127.0.0.1:8000 -t public`
> - **Otra herramienta:** XAMPP, WAMP, Docker, etc.
```

**Credenciales de prueba:**
- **Admin:** `admin@unisant.test` / `password`
- **Sede:** `sede@unisant.test` / `password`
- **Control Escolar:** `ce@unisant.test` / `password`

---

## 📂 Estructura del proyecto

```
app/
  Models/              → Alumno, Programa, Materia, Pago, Sede, Inscripcion, Adeudo, User
  Http/Controllers/    → AlumnoController, ProgramaController, PagoController, DashboardController, ReporteController, Api/AlumnoApiController
  Http/Middleware/     → NivelMiddleware
  Jobs/                → GenerarReporteDeudasJob
database/
  migrations/          → 10 migraciones con datos de prueba
  seeders/             → UserSeeder, SedeSeeder, ProgramaSeeder, AlumnoSeeder
resources/views/
  layouts/app.blade.php
  dashboard/index.blade.php
  alumnos/index.blade.php, create.blade.php
  programas/index.blade.php
  pagos/create.blade.php, index.blade.php
  auth/login.blade.php, register.blade.php
routes/
  web.php              → Rutas principales
  api.php              → Endpoints API
  auth.php             → Login / Register / Logout
```

---

## ⚠️ Estado del proyecto

**Este proyecto NO está completo.** Contiene errores, bugs y malas prácticas deliberadamente introducidos para que el candidato los identifique y solucione.

Los errores abarcan:
- **Base de datos:** campos `text` en lugar de tipos adecuados, sin índices, sin `casts` en modelos.
- **Backend:** N+1 queries, SQL injection potencial, sin paginación, sin transacciones, cálculos incorrectos, validaciones incompletas.
- **Frontend:** selects sin paginación, DataTables sin optimización.
- **Seguridad:** middleware de roles con `==` en lugar de `===`, rutas sin autenticación, endpoints API abiertos, CSRF mal colocado.
- **Jobs:** fallos silenciosos, sin `chunk`, sin manejo de excepciones.
- **Performance:** queries pesadas sin caché, sumas en PHP en lugar de SQL, carga de todos los registros en memoria.

---

## 📖 Documentación adicional

- `docs/INSTRUCCIONES_CANDIDATO.md` – Guía paso a paso para el candidato.
- `docs/PRUEBA_TECNICA.md` – Tareas específicas, tiempos estimados y entregables.
- `docs/EVALUACION.md` – Rúbrica y criterios de evaluación (para el reclutador).

---

## 📨 Entrega

Al finalizar la prueba, el candidato debe:
1. Crear un **repositorio propio** (GitHub, GitLab, Bitbucket, etc.) con el código corregido.
2. Incluir el archivo **`RESPUESTA.md`** en la raíz.
3. Enviar el **enlace al repositorio** al correo: **`ltepepa@unisant.edu.mx`**

---

## 📜 Licencia

Este proyecto es de uso exclusivo para pruebas técnicas de reclutamiento. No debe usarse en producción.
