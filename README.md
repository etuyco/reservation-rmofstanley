# RM of Stanley - Booking and Reservation System

A comprehensive booking and reservation system for RM of Stanley properties including Parks, Conference Rooms, and Equipment.

## Features

- **Property Management**: View and manage properties (Parks, Conference Rooms, Equipment)
- **Booking System**: Home owners can book properties with admin approval
- **Reservation System**: Home owners can reserve properties with admin approval
- **Admin Dashboard**: Admins can approve/reject booking and reservation requests
- **Real-time Status**: Properties show current status (Available, In Use, Reserved)
- **User Roles**: Separate roles for owners and admins
- **Authentication**: Secure login and registration system

## Technology Stack

- **Framework**: Laravel 8.x
- **Frontend**: Blade Templates with Bootstrap 5
- **Database**: MySQL
- **Authentication**: Laravel UI

## Installation

### Prerequisites

- PHP >= 7.3
- Composer
- MySQL
- Node.js and NPM (for assets)

### Steps

1. **Clone or navigate to the project directory**
   ```bash
   cd stanley-booking-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Configure database in `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=stanley_booking
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database with sample data**
   ```bash
   php artisan db:seed
   ```

8. **Install and compile frontend assets (optional)**
   ```bash
   npm install
   npm run dev
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

10. **Access the application**
    - Open your browser and go to: `http://localhost:8000`

## Default Users

After seeding, you can login with:

**Admin Account:**
- Email: `admin@stanley.com`
- Password: `password`

**Owner Account:**
- Email: `owner@stanley.com`
- Password: `password`

## Usage

### For Home Owners

1. **Register/Login**: Create an account or login with existing credentials
2. **Browse Properties**: View all available properties on the home page
3. **View Property Details**: Click on any property to see details and current status
4. **Book or Reserve**: 
   - Click "Book Now" for immediate bookings
   - Click "Reserve" for future reservations
5. **Manage Requests**: View your bookings and reservations in "My Bookings" and "My Reservations"
6. **Wait for Approval**: All requests require admin approval

### For Admins

1. **Login**: Use admin credentials to login
2. **Access Dashboard**: Navigate to "Admin Dashboard" from the menu
3. **Review Requests**: See all pending bookings and reservations
4. **Approve/Reject**: 
   - Click "Approve" to approve a request
   - Click "Reject" to reject with optional reason
5. **View All**: See all bookings and reservations in dedicated pages

## Property Status

Properties display their current status:
- **Available**: Property is free and can be booked/reserved
- **In Use**: Property is currently being used (approved booking is active)
- **Reserved**: Property is currently reserved (approved reservation is active)

## Database Structure

### Tables

- **users**: User accounts (owners and admins)
- **properties**: Available properties (Parks, Conference Rooms, Equipment)
- **bookings**: Booking requests and records
- **reservations**: Reservation requests and records

### Relationships

- Users have many Bookings and Reservations
- Properties have many Bookings and Reservations
- Bookings/Reservations belong to a User and a Property

## Routes

### Public Routes
- `/` - Home page (Properties listing)
- `/properties` - Properties listing
- `/properties/{id}` - Property details
- `/login` - Login page
- `/register` - Registration page

### Authenticated Routes (Owners)
- `/bookings` - My bookings
- `/bookings/create` - Create booking
- `/reservations` - My reservations
- `/reservations/create` - Create reservation

### Admin Routes
- `/admin/dashboard` - Admin dashboard
- `/admin/bookings` - All bookings
- `/admin/reservations` - All reservations
- `/admin/bookings/{id}/approve` - Approve booking
- `/admin/bookings/{id}/reject` - Reject booking
- `/admin/reservations/{id}/approve` - Approve reservation
- `/admin/reservations/{id}/reject` - Reject reservation

## Development

### Running Tests
```bash
php artisan test
```

### Clearing Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## License

This project is proprietary software for RM of Stanley.

## Support

For issues or questions, please contact the development team.
