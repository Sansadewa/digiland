# Digiland Wedding Registry - Routing Guide

## Overview
The wedding registry application is now configured to work with the domain `gift.digiland.space` with a clean URL structure where usernames are the first segment after the domain.

## URL Structure

### Main Registry Pages
- **gift.digiland.space/** - Welcome page (no username provided)
- **gift.digiland.space/john_doe** - John's personalized registry
- **gift.digiland.space/jane_smith** - Jane's personalized registry
- **gift.digiland.space/guest_123** - Guest 123's personalized registry

### API Endpoints
- **gift.digiland.space/get_details?id=1** - Get gift details
- **gift.digiland.space/get_gifts** - Get all gifts
- **gift.digiland.space/book** - Book a gift (POST)
- **gift.digiland.space/confirm_purchase** - Confirm purchase (POST)
- **gift.digiland.space/cancel_booking** - Cancel booking (POST)

## How Routing Works

### Route Priority
1. **API Routes** - These are checked first to avoid conflicts with usernames
2. **Username Routes** - The catch-all route `(:any)` handles usernames

### Route Configuration
```php
// API Routes - These must come BEFORE the catch-all username route
$route['book'] = 'gift/book';
$route['confirm_purchase'] = 'gift/confirm_purchase';
$route['cancel_booking'] = 'gift/cancel_booking';
$route['get_details'] = 'gift/get_details';
$route['get_gifts'] = 'gift/get_gifts';

// Catch-all route for usernames - This must be the LAST route
$route['(:any)'] = 'gift/index/$1';
```

### Controller Logic
The `Gift` controller's `index()` method:
1. Receives the username as a parameter
2. Checks if the user exists in the database
3. Creates a new user if they don't exist
4. Sets up the session
5. Loads the registry view with gift data

## Database Structure

### Users Table
- `id` - Primary key
- `username` - Unique username (used in URLs)
- `created_at` - Timestamp

### Gifts Table
- `id` - Primary key
- `name` - Gift name
- `description` - Gift description
- `price` - Gift price
- `image_url` - Product image
- `store_url` - Link to purchase
- `category` - Gift category
- `status` - available/booked/purchased
- `booked_by_user_id` - Foreign key to users
- `booked_until` - Booking expiration
- `purchased_by_user_id` - Foreign key to users
- `order_number` - Purchase order number

## Setup Instructions

### 1. Database Configuration
Update `application/config/database.php` with your database credentials:
```php
$db['default'] = array(
    'hostname' => 'your_hostname',
    'username' => 'your_username',
    'password' => 'your_password',
    'database' => 'digiland',
    // ... other settings
);
```

### 2. Domain Configuration
The base URL is already set in `application/config/config.php`:
```php
$config['base_url'] = 'https://gift.digiland.space';
```

### 3. .htaccess
The `.htaccess` file is already configured for clean URLs:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

### 4. Testing
Test the following URLs:
- `gift.digiland.space/` - Should show welcome page
- `gift.digiland.space/test_user` - Should show registry for test_user
- `gift.digiland.space/get_gifts` - Should return JSON of all gifts

## Security Considerations

1. **Username Validation** - The system automatically creates users when they visit their URL
2. **Session Management** - Each user gets a unique session based on their username
3. **Booking Limits** - Users can only book one gift at a time
4. **Booking Expiration** - Bookings expire after 15 minutes
5. **Purchase Verification** - Only the user who booked can confirm the purchase

## Troubleshooting

### Common Issues

1. **404 Errors** - Check that the `.htaccess` file is in the root directory
2. **Database Connection** - Verify database credentials in `database.php`
3. **Routing Issues** - Ensure routes are in the correct order (API routes first)
4. **Session Problems** - Check that sessions are enabled in the controller

### Debug Mode
To enable debug mode, set the environment in `index.php`:
```php
define('ENVIRONMENT', 'development');
```

## File Structure
```
gift/
├── application/
│   ├── config/
│   │   ├── config.php (base_url, index_page)
│   │   ├── database.php (database settings)
│   │   └── routes.php (routing rules)
│   ├── controllers/
│   │   └── Gift.php (main controller)
│   ├── models/
│   │   └── Gift_model.php (database operations)
│   └── views/
│       ├── gift.php (main registry view)
│       └── welcome.php (welcome page)
├── .htaccess (URL rewriting)
└── index.php (entry point)
``` 