# Quick Setup Guide

## 1. Database Setup

Create a MySQL database:
```sql
CREATE DATABASE stanley_booking;
```

## 2. Environment Configuration

Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stanley_booking
DB_USERNAME=root
DB_PASSWORD=your_password
```

## 3. Run Migrations and Seeders

```bash
php artisan migrate
php artisan db:seed
```

## 4. Start Server

```bash
php artisan serve
```

## 5. Access the Application

- URL: http://localhost:8000
- Admin Login: admin@stanley.com / password
- Owner Login: owner@stanley.com / password

## Sample Properties Created

After seeding, you'll have:
- Community Park
- Conference Room A
- Conference Room B
- Projector Equipment
- Sound System

## Next Steps

1. Create additional properties through database or admin interface
2. Customize the styling as needed
3. Add email notifications (optional)
4. Configure production environment

