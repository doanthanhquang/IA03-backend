# Backend API - Laravel Authentication & Email Service

Laravel 12 REST API with JWT-style authentication, Google OAuth support, and mock email service.

## 🚀 Setup and Run Instructions

### Prerequisites
- PHP 8.2 or higher
- Composer
- SQLite or MySQL database
- Terminal access

### Local Setup

1. **Install Dependencies**
```bash
cd IA03-backend
composer install
```

2. **Environment Configuration**
```bash
# Copy environment file
cp env.example .env

# Generate application key
php artisan key:generate
```

3. **Configure Database**

The default configuration uses SQLite (recommended for development):
```env
DB_CONNECTION=sqlite
```

Database file will be created automatically at `database/database.sqlite`

For MySQL, update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=user_registration
DB_USERNAME=root
DB_PASSWORD=your_password
```

4. **Run Migrations**
```bash
php artisan migrate
```

This creates:
- `users` table (with Google OAuth support)
- `auth_tokens` table (access & refresh tokens)
- Other Laravel system tables

5. **Configure CORS**

Set your frontend URL in `.env`:
```env
FRONTEND_URL=http://localhost:5173
```

6. **Start Development Server**
```bash
php artisan serve
```

Server runs at: **http://localhost:8000**

### Verify Installation

Test the API is running:
```bash
curl http://localhost:8000/api/mailboxes
```

Expected response: 401 Unauthorized (correct, endpoint requires authentication)

## 🌐 Public Hosting URL and Deployment

### Deployed API
**Live URL**: `https://your-backend.railway.app` (replace with your actual Railway URL)

This API is deployed on **Railway**, a modern platform for deploying backend applications with automatic CI/CD from Git.

### Deployment Platform: Railway

**Why Railway?**
- ✅ Simple deployment from GitHub repository
- ✅ Automatic builds on push to main branch
- ✅ Built-in MySQL/PostgreSQL database provisioning
- ✅ Environment variable management
- ✅ Automatic HTTPS/SSL certificates
- ✅ Easy PHP/Laravel support with Nixpacks

**Deployment Steps:**

1. **Connect Repository**
   - Visit [Railway.app](https://railway.app/)
   - Sign in with GitHub
   - Click "New Project" → "Deploy from GitHub repo"
   - Select your backend repository

2. **Add Database Service**
   - In Railway project, click "New" → "Database" → "Add MySQL" (or PostgreSQL)
   - Railway will automatically create database and provide connection details
   - Database credentials are auto-injected as environment variables

3. **Configure Environment Variables**
   
   In Railway dashboard, add these variables:
   ```env
   APP_NAME=UserRegistrationAPI
   APP_ENV=production
   APP_KEY=base64:your_generated_key
   APP_DEBUG=false
   APP_URL=https://your-backend.railway.app
   
   # Database (automatically set by Railway MySQL service)
   DB_CONNECTION=mysql
   DB_HOST=${MYSQLHOST}
   DB_PORT=${MYSQLPORT}
   DB_DATABASE=${MYSQLDATABASE}
   DB_USERNAME=${MYSQLUSER}
   DB_PASSWORD=${MYSQLPASSWORD}
   
   # CORS - Add your frontend Railway URL
   FRONTEND_URL=https://your-frontend.railway.app
   ```

4. **Configure Build Settings**
   
   Create `nixpacks.toml` in project root (Railway auto-detects Laravel):
   ```toml
   [phases.setup]
   nixPkgs = ["php82", "php82Extensions.pdo", "php82Extensions.pdo_mysql"]
   
   [phases.install]
   cmds = ["composer install --optimize-autoloader --no-dev"]
   
   [phases.build]
   cmds = ["php artisan config:cache", "php artisan route:cache"]
   
   [start]
   cmd = "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"
   ```

5. **Deploy**
   - Railway automatically deploys on push to main branch
   - Migrations run automatically on startup
   - Get your public URL from Railway dashboard

6. **Verify Deployment**
   
   Test the deployed API:
   ```bash
   curl https://your-backend.railway.app/api/mailboxes
   ```
   
   Expected: 401 Unauthorized (correct, requires authentication)

7. **Update Frontend Configuration**
   - Update frontend `.env` or Railway environment variables
   - Set `VITE_API_URL=https://your-backend.railway.app`
   - Redeploy frontend if needed

### Reproduce Deployment Locally

To test production configuration on your local machine:

1. **Install Production Dependencies**
```bash
composer install --optimize-autoloader --no-dev
```

2. **Set Production Environment**

Create or update `.env.production`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_production_test_db
DB_USERNAME=root
DB_PASSWORD=your_password

FRONTEND_URL=http://localhost:5173
```

3. **Cache Configuration**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

4. **Run Migrations**
```bash
php artisan migrate --force
```

5. **Start Production Server**
```bash
php artisan serve --env=production --host=0.0.0.0 --port=8000
```

The API will run at: **http://localhost:8000**

6. **Test Production Build**
   - Test all API endpoints
   - Verify authentication works
   - Check token refresh mechanism
   - Test Google Sign-In endpoint
   - Verify CORS settings

7. **Clear Caches** (when done testing)
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### Railway-Specific Configuration

**Database Connection:**
Railway provides these environment variables automatically:
- `MYSQLHOST` - Database host
- `MYSQLPORT` - Database port
- `MYSQLDATABASE` - Database name
- `MYSQLUSER` - Database username
- `MYSQLPASSWORD` - Database password

**Port Configuration:**
Railway sets `PORT` environment variable dynamically. Use:
```php
// In your start command
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
```

**Public Networking:**
Railway automatically provides a public URL with SSL certificate.

### Deployment Checklist

Before deploying to production:

- ✅ Set `APP_ENV=production` and `APP_DEBUG=false`
- ✅ Generate new `APP_KEY` for production
- ✅ Configure Railway MySQL/PostgreSQL database
- ✅ Set all required environment variables
- ✅ Add frontend URL to `FRONTEND_URL` variable
- ✅ Update CORS configuration in `config/cors.php`
- ✅ Test database connection
- ✅ Run migrations with `--force` flag
- ✅ Verify API endpoints work correctly
- ✅ Test authentication and token refresh
- ✅ Check error logging and monitoring

### Troubleshooting Railway Deployment

**Common Issues:**

1. **Database Connection Failed**
   - Verify Railway database service is running
   - Check environment variables are correctly mapped
   - Ensure `DB_CONNECTION=mysql` (not sqlite)

2. **Migration Errors**
   - Make sure migrations run with `--force` flag
   - Check database credentials in Railway dashboard
   - Verify database is accessible

3. **500 Internal Server Error**
   - Check Railway logs: `railway logs`
   - Verify `APP_KEY` is set
   - Ensure all required extensions are installed

4. **CORS Errors**
   - Verify `FRONTEND_URL` environment variable
   - Check `config/cors.php` configuration
   - Restart Railway service after env changes

5. **Port Binding Issues**
   - Ensure using `--host=0.0.0.0`
   - Use `${PORT:-8000}` for dynamic port binding

## 🔐 Token Storage and Security Considerations

### Token Architecture

This API implements a dual-token authentication system:

#### Access Tokens
- **Storage**: Not stored by backend, only validated
- **Lifetime**: 15 minutes
- **Format**: Random 64-character string
- **Purpose**: Short-lived authorization for API requests
- **Transmission**: Via `Authorization: Bearer {token}` header

**Security Benefits**:
- ✅ Short lifetime limits exposure window
- ✅ Compromised token expires quickly
- ✅ Must be renewed frequently
- ✅ Reduces risk of long-term unauthorized access

#### Refresh Tokens
- **Storage**: Database (`auth_tokens` table)
- **Lifetime**: 7 days
- **Format**: Random 64-character string
- **Purpose**: Obtain new access tokens without re-login
- **Transmission**: Via request body to `/api/refresh`

**Security Benefits**:
- ✅ Stored server-side with expiration tracking
- ✅ Can be revoked immediately (logout)
- ✅ Tied to specific user
- ✅ Only valid for token refresh endpoint

### Token Storage in Database

```sql
-- auth_tokens table
access_token        VARCHAR(255)  -- Current access token
access_expires_at   TIMESTAMP     -- Access token expiration
refresh_token       VARCHAR(255)  -- Refresh token
refresh_expires_at  TIMESTAMP     -- Refresh token expiration
revoked             BOOLEAN       -- Revocation flag
user_id             BIGINT        -- Associated user
```

**Why Database Storage?**
- ✅ Centralized token management
- ✅ Instant revocation on logout
- ✅ Can track active sessions
- ✅ Audit trail of token usage
- ✅ Enables security features (device management, suspicious activity detection)

**Trade-offs**:
- ⚠️ Database query on every request (mitigated by indexing)
- ⚠️ Requires cleanup of expired tokens (scheduled job recommended)

### Security Implementations

#### Password Security
```php
// Hashing (bcrypt with cost factor 12)
$user->password = Hash::make($request->password);

// Verification
Hash::check($request->password, $user->password);
```

**Benefits**:
- ✅ Industry-standard bcrypt algorithm
- ✅ Automatic salt generation
- ✅ Configurable cost factor
- ✅ Resistant to rainbow table attacks

#### Token Generation
```php
// Cryptographically secure random tokens
$accessToken = Str::random(64);  // 64 characters = 256 bits entropy
$refreshToken = Str::random(64);
```

**Benefits**:
- ✅ Uses PHP's `random_bytes()` (cryptographically secure)
- ✅ High entropy (2^256 possible combinations)
- ✅ Impossible to predict or brute force
- ✅ Unique per session

#### Token Validation
```php
// Middleware checks every protected request
$token = AuthToken::where('access_token', $bearerToken)
    ->where('revoked', false)
    ->first();

if (!$token || $token->access_expires_at->isPast()) {
    return response()->json(['message' => 'Unauthorized'], 401);
}
```

**Benefits**:
- ✅ Validates token exists and not revoked
- ✅ Checks expiration time
- ✅ Returns 401 if invalid (triggers frontend refresh)
- ✅ Protects all sensitive endpoints

#### CORS Protection
```php
// config/cors.php
'allowed_origins' => [
    env('FRONTEND_URL', 'http://localhost:5173'),
],
'supports_credentials' => true,
```

**Benefits**:
- ✅ Only specified origins can access API
- ✅ Prevents unauthorized cross-origin requests
- ✅ Credentials (tokens) protected
- ✅ Configurable per environment

### Security Best Practices Implemented

1. **Token Rotation**: New access token on every refresh
2. **Token Revocation**: All tokens revoked on logout
3. **Expiration Enforcement**: Expired tokens rejected automatically
4. **Rate Limiting**: Laravel's built-in throttling (configurable)
5. **Input Validation**: All requests validated before processing
6. **SQL Injection Prevention**: Eloquent ORM with parameter binding
7. **XSS Prevention**: JSON responses automatically escaped
8. **HTTPS Required**: Production should use HTTPS (configured at server level)

### Recommended Additional Security

For production deployment:

1. **HTTPS Only**: Configure web server to redirect HTTP to HTTPS
2. **Rate Limiting**: Implement aggressive rate limits on auth endpoints
3. **Token Cleanup**: Schedule job to delete expired tokens
```php
// In Laravel scheduler
$schedule->call(function () {
    AuthToken::where('refresh_expires_at', '<', now())->delete();
})->daily();
```
4. **Security Headers**: Add security headers in web server config
5. **Database Encryption**: Enable encryption at rest for database
6. **Audit Logging**: Log authentication attempts and token usage
7. **IP Whitelisting**: Optional for admin/sensitive endpoints

## 🔌 Third-Party Services Used

### 1. Google OAuth 2.0

**Purpose**: Enable users to sign in with their Google account

**Implementation**:
- Frontend sends Google credential (JWT) to backend
- Backend validates and extracts user info
- Creates/updates user account
- Issues app tokens (access + refresh)

**Configuration**: None required on backend (frontend handles OAuth)

**Endpoint**: `POST /api/google-signin`

**User Data Stored**:
- `google_id`: Unique Google user identifier
- `email`: User's Google email
- `name`: User's display name
- `avatar`: Profile picture URL
- `provider`: Set to 'google'

**Security Notes**:
- In production, should verify Google JWT signature
- Currently trusts frontend validation (acceptable for assignment)
- Google credentials never stored, only user info

### 2. Composer (PHP Dependencies)

**Purpose**: PHP package management

**Key Dependencies**:
- `laravel/framework`: ^12.0 - Core framework
- `laravel/tinker`: ^2.10 - Interactive shell
- `fakerphp/faker`: ^1.23 - Fake data generation (dev)
- `phpunit/phpunit`: ^11.5 - Testing framework (dev)

**Installation**: `composer install`

### 3. Laravel (Framework)

**Purpose**: PHP framework providing authentication, routing, ORM, etc.

**Features Used**:
- Eloquent ORM (database)
- Routing (API endpoints)
- Validation (input validation)
- Hash (password hashing)
- Middleware (authentication)
- Migrations (database schema)

### 4. Database (SQLite/MySQL)

**Purpose**: Store user accounts, tokens, and application data

**Default**: SQLite (file-based, zero configuration)
**Production**: MySQL/PostgreSQL recommended

### 5. Railway (Hosting Platform)

**Purpose**: Deploy and host the Laravel API with database

**Why Railway?**
- Simple deployment from GitHub
- Automatic CI/CD pipeline
- Built-in database provisioning (MySQL/PostgreSQL)
- Free tier with generous limits ($5 credit/month)
- Automatic SSL/HTTPS certificates
- Environment variable management
- Nixpacks support for Laravel/PHP

**Features Used**:
- Web service hosting (PHP/Laravel)
- MySQL database service
- Environment variable injection
- Automatic builds on git push
- Custom domain support
- Service logs and monitoring

**Configuration:**
- Build: Nixpacks auto-detection for Laravel
- Start command: `php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}`
- Database: Railway MySQL service (auto-configured)
- Environment variables: Managed in Railway dashboard

**Database Variables** (auto-injected by Railway):
- `MYSQLHOST` - Database hostname
- `MYSQLPORT` - Database port
- `MYSQLDATABASE` - Database name
- `MYSQLUSER` - Database username
- `MYSQLPASSWORD` - Database password

**Cost**: Free tier available with $5 credit/month, paid plans from $5/month

**Deployment**: Automatic on push to main branch

**Public URL**: `https://your-backend.railway.app`

**Documentation**: https://docs.railway.app/

## 🛠️ Tech Stack

- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: SQLite (dev) / MySQL (production)
- **Authentication**: JWT-style tokens (custom)
- **Password Hashing**: Bcrypt
- **Package Manager**: Composer

## 📝 License

MIT License - Free to use for educational purposes.

---

**Backend API ready for production deployment! 🚀**
