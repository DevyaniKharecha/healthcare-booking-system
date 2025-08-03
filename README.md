# Healthcare Booking API

A Laravel 12 RESTful API for managing healthcare appointments: registration, viewing professionals, booking, canceling (with 24-hour constraint), and marking completed. Built with Passport, MySQL, PHPUnit setup.

# Features
- User registration & login (Passport token-based auth)
- List healthcare professionals
- Book an appointment (availability check)
- View user’s upcoming and past appointments
- Cancel (disallowed within 24 hours)
- Mark appointment as completed
- Seed script for dummy professionals
- Unit tests for business logic
- Postman collection included - "healthcare-api.postman_collection.json"
- Docker + Docker Compose support

# Requirements
- PHP 8.3, Composer
- MySQL 8.0

# Setup Instructions

# Local (without Docker)
bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan passport:install
npm install && npm run dev (if frontend)
php artisan serve
