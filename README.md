# Stationery POS - Inventory Management System

A complete Point of Sale (POS) and Inventory Management System built for stationery shops using Laravel, Blade, and MySQL.

## Features

- **Dashboard**: Overview of daily/monthly sales, low stock alerts, top selling products
- **Point of Sale (POS)**: Easy-to-use interface for quick sales with barcode/SKU search
- **Product Management**: Full CRUD for products with categories, stock tracking, images
- **Category Management**: Organize products by categories
- **Sales History**: View all transactions with cancel/refund capability
- **Reports**:
  - Daily Sales Report with hourly breakdown
  - Monthly Sales Report with trends and charts
  - Inventory Report with stock levels
  - Profit Report with margin analysis
- **Stock Management**: Low stock alerts, stock adjustment

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Node.js & NPM (optional, for asset compilation)

## Installation

1. **Clone or download the project**
   ```bash
   cd pos
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Create environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Configure database**
   
   Edit `.env` file and set your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=stationery_pos
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

6. **Create the database**
   ```sql
   CREATE DATABASE stationery_pos;
   ```

7. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Seed the database (creates admin user and sample data)**
   ```bash
   php artisan db:seed
   ```

9. **Create storage link (for product images)**
   ```bash
   php artisan storage:link
   ```

10. **Start the development server**
    ```bash
    php artisan serve
    ```

11. **Access the application**
    
    Open http://localhost:8000 in your browser

## Default Login Credentials

- **Email**: admin@pos.com
- **Password**: password

## Directory Structure

```
pos/
├── app/
│   ├── Http/Controllers/     # All controllers
│   ├── Models/               # Eloquent models
│   └── Providers/            # Service providers
├── config/                   # Configuration files
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── public/                   # Public assets
├── resources/views/          # Blade templates
│   ├── layouts/              # Main layout
│   ├── auth/                 # Login page
│   ├── dashboard/            # Dashboard view
│   ├── categories/           # Category CRUD views
│   ├── products/             # Product CRUD views
│   ├── pos/                  # POS interface
│   └── reports/              # Report views
└── routes/                   # Route definitions
```

## Usage Guide

### Point of Sale
1. Navigate to "Point of Sale" from sidebar
2. Search products by name, SKU, or barcode
3. Click products to add to cart
4. Adjust quantities as needed
5. Apply discounts if any
6. Click "Checkout" and complete payment

### Managing Products
1. Go to "Products" in sidebar
2. Add new products with category, pricing, and stock info
3. Set alert quantity for low stock notifications
4. Upload product images (optional)

### Viewing Reports
1. **Daily Report**: See today's sales breakdown by hour
2. **Monthly Report**: Analyze monthly trends and top products
3. **Inventory Report**: Check stock levels across all products
4. **Profit Report**: Analyze profit margins by date range

## License

This project is open-source software.
