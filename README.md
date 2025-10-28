# Backend - User Registration API (Laravel)

Laravel backend API for user registration with MySQL.

## 🚀 Quick Start

### Installation
```bash
composer install
```

### Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

**Configure `.env`:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=user_registration
DB_USERNAME=root
DB_PASSWORD=your_password

FRONTEND_URL=http://localhost:5173
```

### Database Setup
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE user_registration;"

# Run migrations
php artisan migrate
```

### Run Development Server
```bash
php artisan serve
```

Server will start at `http://localhost:8000`

## 📡 API Endpoints

### POST /api/user/register
Register a new user

**Request:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response (Success - 201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "createdAt": "2025-10-26T12:00:00.000000Z"
  }
}
```

**Response (Error - 400):**
```json
{
  "success": false,
  "message": "Email already exists",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

## 🔒 Security Features
- Password hashing with Laravel Hash (bcrypt)
- Input validation with Laravel Validator
- Unique email constraint
- CORS protection
- Secure environment variables

## 🗄️ Database Schema

### users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## 📦 Tech Stack
- Laravel 12
- MySQL
- PHP 8.2+
- Composer

## 🧪 Testing with cURL
```bash
# Test registration
curl -X POST http://localhost:8000/api/user/register \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# Test duplicate email (should fail)
curl -X POST http://localhost:8000/api/user/register \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'
```

## 🛠️ Laravel Commands
```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Create migration
php artisan make:migration create_table_name

# Create controller
php artisan make:controller ControllerName

# Create model
php artisan make:model ModelName

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# List routes
php artisan route:list
```

## 📂 Project Structure
```
backend/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── UserController.php
│   └── Models/
│       └── User.php
├── config/
│   ├── cors.php
│   └── database.php
├── database/
│   └── migrations/
│       └── 2024_01_01_000000_create_users_table.php
├── routes/
│   ├── api.php
│   └── web.php
└── .env
```

## 🌐 CORS Configuration
CORS is configured in `config/cors.php` to allow requests from the React frontend.

Allowed origin is set via `FRONTEND_URL` environment variable.

## 🚀 Deployment
- Update `.env` with production database credentials
- Set `APP_ENV=production`
- Set `APP_DEBUG=false`
- Run `composer install --optimize-autoloader --no-dev`
- Run `php artisan config:cache`
- Run `php artisan route:cache`
- Set up web server (Apache/Nginx) to point to `public/` directory
