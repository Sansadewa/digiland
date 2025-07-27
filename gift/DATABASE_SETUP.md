# Digiland Wedding Registry - Database Setup

## Database Schema

### 1. Users Table
```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `show_gift_section` BOOLEAN NOT NULL DEFAULT FALSE,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2. Gifts Table
```sql
CREATE TABLE `gifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `store_url` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `status` enum('available','booked','purchased') NOT NULL DEFAULT 'available',
  `booked_by_user_id` int(11) DEFAULT NULL,
  `booked_until` datetime DEFAULT NULL,
  `purchased_by_user_id` int(11) DEFAULT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booked_by_user_id` (`booked_by_user_id`),
  KEY `purchased_by_user_id` (`purchased_by_user_id`),
  CONSTRAINT `gifts_ibfk_1` FOREIGN KEY (`booked_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `gifts_ibfk_2` FOREIGN KEY (`purchased_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3. Admin Users Table
```sql
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Sample Data

### Insert Sample Users
```sql
INSERT INTO `users` (`username`, `name`, `phone`, `created_at`) VALUES 
('john_doe', 'John Doe', '+6281234567890', NOW()),
('jane_smith', 'Jane Smith', '+6281234567891', NOW()),
('guest_123', 'Guest User', '+6281234567892', NOW()),
('family_member', 'Family Member', '+6281234567893', NOW()),
('friend_001', 'Friend One', '+6281234567894', NOW());
```

### Insert Sample Gifts
```sql
INSERT INTO `gifts` (`name`, `description`, `price`, `image_url`, `store_url`, `category`, `status`) VALUES 
('AZKO Kris Hair Dryer Travel 600 Watt', 'Compact travel hair dryer with 600W power, perfect for on-the-go styling', 104900.00, 'https://placehold.co/400x400/EAD9D5/333?text=Hair+Dryer', 'https://example.com/hair-dryer', 'Electronics', 'available'),
('Informa Filio Meja Setrika Lipat Classic', 'Foldable ironing board for home use with sturdy construction', 230000.00, 'https://placehold.co/400x400/E0E0E0/333?text=Ironing+Board', 'https://example.com/ironing-board', 'Home', 'available'),
('Le Creuset Signature Round Dutch Oven', 'Premium cast iron dutch oven for cooking, perfect for slow cooking and braising', 4500000.00, 'https://placehold.co/400x400/E74C3C/333?text=Dutch+Oven', 'https://example.com/dutch-oven', 'Kitchen', 'available'),
('Philips Airfryer NA220/10 - 4.2 L', 'Large capacity air fryer for healthy cooking with digital controls', 1085000.00, 'https://placehold.co/400x400/F2F3F4/333?text=Airfryer', 'https://example.com/airfryer', 'Kitchen', 'available'),
('Bose QuietComfort Ultra Headphones', 'Premium noise-cancelling headphones with superior sound quality', 6200000.00, 'https://placehold.co/400x400/5D6D7E/333?text=Headphones', 'https://example.com/headphones', 'Electronics', 'available'),
('Nespresso VertuoPlus Coffee Machine', 'Automatic coffee machine with capsule system for perfect espresso', 3500000.00, 'https://placehold.co/400x400/34495E/333?text=Nespresso', 'https://example.com/nespresso', 'Kitchen', 'available'),
('Informa Gio Lemari Pakaian 2 Pintu Putih', 'White 2-door wardrobe for bedroom organization', 979000.00, 'https://placehold.co/400x400/FDFEFE/333?text=Wardrobe', 'https://example.com/wardrobe', 'Home', 'available'),
('COOGER Alat Pel Lantai Putir', 'Spin mop cleaning tool for efficient floor cleaning', 223600.00, 'https://placehold.co/400x400/D4E6F1/333?text=Spin+Mop', 'https://example.com/spin-mop', 'Home', 'available'),
('Krisbow Cordless Electric Cleaning Brush', 'Cordless cleaning brush for various surfaces', 448700.00, 'https://placehold.co/400x400/CFD8DC/333?text=Cleaning+Brush', 'https://example.com/cleaning-brush', 'Home', 'available'),
('LocknLock Food Dehydrator', 'Food dehydrator for preserving fruits and making healthy snacks', 899000.00, 'https://placehold.co/400x400/F5B7B1/333?text=Dehydrator', 'https://example.com/dehydrator', 'Kitchen', 'available');
```

### Insert Default Admin User
```sql
INSERT INTO `admin_users` (`username`, `password`, `name`, `email`, `is_active`) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@digiland.space', 1);
-- Default password is 'password' (hashed with bcrypt)
```

## Database Configuration

### Update application/config/database.php
```php
$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'your_database_username',
    'password' => 'your_database_password',
    'database' => 'digiland',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cache_dir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
```

## Installation Steps

1. **Create Database**
   ```sql
   CREATE DATABASE digiland CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE digiland;
   ```

2. **Run Table Creation Scripts**
   - Execute the CREATE TABLE statements above in order

3. **Insert Sample Data**
   - Run the INSERT statements for sample data

4. **Update Database Configuration**
   - Modify `application/config/database.php` with your credentials

5. **Test the Application**
   - Visit `gift.digiland.space/test_user`
   - Login to admin panel at `gift.digiland.space/admin` (username: admin, password: password)

## Default Admin Credentials

- **Username**: admin
- **Password**: password
- **URL**: gift.digiland.space/admin

**Important**: Change the default admin password after first login! 