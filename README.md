# 🚢 CCA IA Cotizador

**Cotizador inteligente de logística internacional** para [Caribberan Cargo Agency](https://ccargo.co), impulsado por **Google Gemini 2.0 Flash**.

Genera estimaciones de flete marítimo FCL/LCL desde puertos colombianos, con navieras recomendadas, documentos requeridos y alertas de comercio exterior.

---

## 🛠 Stack Tecnológico

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 12 + PHP 8.x |
| Base de datos | SQLite (local) / PostgreSQL (producción) |
| Frontend | Blade + Alpine.js (CDN) + Tailwind CSS (CDN) |
| IA | Google Gemini 2.0 Flash |
| Deploy | Render.com (Docker) |

---

## ⚡ Instalación Local

### Prerrequisitos
- PHP 8.1+ con extensiones: `pdo_sqlite`, `mbstring`, `openssl`, `xml`, `ctype`, `json`, `bcmath`
- Composer 2.x

### Pasos

```bash
# 1. Clonar el repositorio
git clone <repo-url> cca-cotizador
cd cca-cotizador

# 2. Instalar dependencias
composer install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Agregar tu API Key de Gemini en .env
# GEMINI_API_KEY=AIza...
# Obtenla en: https://aistudio.google.com/app/apikey

# 5. Ejecutar migraciones (SQLite por defecto)
php artisan migrate

# 6. Iniciar servidor
php artisan serve
```

Abre **http://localhost:8000** en tu navegador.

---

## 🐘 Configuración con PostgreSQL (local)

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cca_cotizador
DB_USERNAME=postgres
DB_PASSWORD=tu_password
```

---

## 📧 Configuración de Email

Para que las notificaciones de leads lleguen a `ingeniero.ambiental@ccargo.co`, configura en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu@gmail.com
MAIL_PASSWORD=app_password_gmail
MAIL_ENCRYPTION=tls
```

En desarrollo, `MAIL_MAILER=log` escribe los emails en `storage/logs/laravel.log`.

---

## 🚀 Deploy en Render.com

### Paso 1: Preparar el repositorio
```bash
git init
git add .
git commit -m "feat: CCA IA Cotizador inicial"
git remote add origin https://github.com/tu-usuario/cca-cotizador.git
git push -u origin main
```

### Paso 2: Crear servicio en Render

1. Ir a [render.com](https://render.com) → **New** → **Blueprint**
2. Conectar tu repositorio de GitHub
3. Render detectará el `render.yaml` automáticamente
4. Hará clic en **Apply** — creará el servicio web + la base de datos PostgreSQL

### Paso 3: Configurar variables de entorno secretas

En el dashboard de Render, ir al servicio → **Environment** y agregar:

| Variable | Valor |
|----------|-------|
| `GEMINI_API_KEY` | Tu API key de Google AI Studio |
| `MAIL_USERNAME` | Tu email para SMTP |
| `MAIL_PASSWORD` | Tu contraseña de aplicación |

### Paso 4: Ejecutar migraciones en producción

En el shell de Render (servicio → **Shell**):
```bash
php artisan migrate --force
```

O agrega un **Start Command** en render.yaml que corra migraciones al iniciar.

---

## 📁 Estructura del Proyecto

```
├── app/
│   ├── Http/Controllers/
│   │   ├── CotizadorController.php   # Formulario + llamada a Gemini
│   │   └── LeadController.php        # Captura de contactos
│   ├── Http/Requests/
│   │   ├── CotizacionRequest.php     # Validación del formulario
│   │   └── LeadRequest.php           # Validación del modal
│   ├── Models/
│   │   ├── Cotizacion.php
│   │   └── Lead.php
│   └── Services/
│       └── GeminiCotizadorService.php  # Integración con Gemini AI
├── resources/views/
│   ├── layouts/app.blade.php           # Layout principal
│   └── cotizador/index.blade.php       # Vista principal + Alpine.js
├── database/migrations/
│   ├── ..._create_cotizaciones_table.php
│   └── ..._create_leads_table.php
├── routes/web.php
├── Dockerfile
├── nginx.conf
├── supervisord.conf
├── render.yaml
└── .env.example
```

---

## 🔑 Variables de Entorno Requeridas

| Variable | Descripción | Requerida |
|----------|-------------|-----------|
| `APP_KEY` | Clave de cifrado de Laravel | ✅ Sí |
| `GEMINI_API_KEY` | API Key de Google Gemini | ✅ Sí |
| `DATABASE_URL` | URL de conexión PostgreSQL (Render) | En producción |
| `MAIL_*` | Configuración SMTP para notificaciones | Recomendada |

---

## 📄 Licencia

Propiedad de **Caribberan Cargo Agency** — Todos los derechos reservados.

> Las tarifas generadas son estimativas y no constituyen una cotización oficial.
