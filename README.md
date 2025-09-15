# Laravel Starter

A ready-to-use Laravel development stack with Docker, API endpoints, and comprehensive testing.

## Features

- 🐳 **Docker Environment** - Independent of OS
- 🚀 **Laravel API** - RESTful endpoints with Swagger documentation
- 👥 **User Management** - Full CRUD operations
- 🧪 **Test Suite** - Feature and Unit tests
- 📚 **API Documentation** - Auto-generated Swagger UI

## Quick Start

1. **Clone and setup:**
   ```bash
   git clone <repository-url>
   cd laravel-starter
   cp .env.example .env
   ```

2. **Start with Docker:**
   ```bash
   docker-compose up -d
   ```

3. **Access the application:**
   - API: http://localhost:8010
   - Documentation: http://localhost:8010/api/docs
   - phpMyAdmin: http://localhost:8080

## Available Services

- **App** (Laravel + Nginx) - Port 8010
- **MySQL** - Port 3306
- **phpMyAdmin** - Port 8080

## API Endpoints

- `GET /` - Status endpoint
- `GET /up` - Health check
- `GET /api/docs` - API documentation
- `GET /api/users` - List users
- `POST /api/users` - Create user
- `GET /api/users/{id}` - Get user
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user

## Testing

```bash
docker-compose exec app php artisan test
```

## Development

```bash
# Install dependencies
docker-compose exec app composer install

# Run migrations
docker-compose exec app php artisan migrate

# Generate API docs
docker-compose exec app php artisan l5-swagger:generate
```

## License

MIT