# 🚀 Laravel Backend - Quick Start

## Setup Instructions

1. **Install Dependencies**
```bash
composer install
```

2. **Configure Environment**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Update Database Settings in .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=user_registration
DB_USERNAME=root
DB_PASSWORD=your_password
```

4. **Create Database**
```bash
mysql -u root -p -e "CREATE DATABASE user_registration;"
```

5. **Run Migrations**
```bash
php artisan migrate
```

6. **Start Server**
```bash
php artisan serve
```

## 📡 API Endpoint

**POST /api/user/register**
- URL: http://localhost:8000/api/user/register
- Method: POST
- Body: `{"email": "test@test.com", "password": "password123"}`

## 🧪 Test with cURL

```bash
curl -X POST http://localhost:8000/api/user/register \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'
```

## 📂 Key Files

- `app/Http/Controllers/UserController.php` - Registration logic
- `app/Models/User.php` - User model
- `database/migrations/..._create_users_table.php` - Database schema
- `routes/api.php` - API routes
- `config/cors.php` - CORS configuration

## ✅ You're Ready!

Backend API is now running at: **http://localhost:8000**


