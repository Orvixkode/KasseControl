# KasseControl Setup Instructions

## ✅ Completed Steps

1. ✅ Composer dependencies installed
2. ✅ NPM dependencies installed
3. ✅ Application key generated
4. ✅ All middleware files created
5. ✅ Auth controllers created
6. ✅ Login view created

## 🔧 Remaining Setup Steps

### Step 1: Configure Database

Edit the `.env` file and set your MySQL credentials:

```bash
nano /home/rakin/github/Projects/KasseControl/.env
```

Update these lines:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kasse_pos
DB_USERNAME=root
DB_PASSWORD=your_mysql_password_here
```

### Step 2: Create Database

Login to MySQL and create the database:

```bash
mysql -u root -p
```

Then run:
```sql
CREATE DATABASE kasse_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

### Step 3: Run Migrations and Seeders

```bash
cd /home/rakin/github/Projects/KasseControl
php artisan migrate --seed
```

This will create all tables and seed them with test data:
- 3 users (admin, manager, cashier)
- 3 categories
- 4 products
- 2 customers
- 1 supplier

### Step 4: Set Storage Permissions

```bash
chmod -R 775 storage bootstrap/cache
```

### Step 5: Start Development Servers

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Vite Dev Server:**
```bash
npm run dev
```

### Step 6: Access the Application

Open your browser and visit:
```
http://localhost:8000
```

## 🔐 Default Login Credentials

| Role    | Email                  | Password |
|---------|------------------------|----------|
| Admin   | admin@kasse.local      | password |
| Manager | manager@kasse.local    | password |
| Cashier | cashier@kasse.local    | password |

## 📱 Available Routes

After login, you can access:

- **Dashboard**: `/dashboard` - Overview with stats, recent sales, low stock alerts
- **POS**: `/pos` - Point of Sale interface for making sales
- **Products**: `/products` - Product management (CRUD)
- **Sales**: `/sales` - Sales history and management
- **Purchases**: `/purchases` - Purchase orders management

## 🎯 Key Features

1. **Role-Based Access Control (RBAC)**
   - Admin: Full access
   - Manager: Products, sales, purchases
   - Cashier: POS and sales only

2. **Inventory Management**
   - Automatic stock updates on sales/purchases
   - Stock movement tracking
   - Low stock alerts on dashboard

3. **POS System**
   - Quick product search
   - Shopping cart with Alpine.js
   - Customer selection
   - Payment processing

4. **Sales Management**
   - Invoice generation
   - Payment tracking
   - Sales reports

5. **Purchase Management**
   - Purchase orders
   - Supplier management
   - Automatic stock updates

## 🛠 Troubleshooting

### Issue: "Access denied for user 'root'@'localhost'"
**Solution**: Update `DB_PASSWORD` in `.env` file with your MySQL password

### Issue: "Class 'Redis' not found"
**Solution**: Change `CACHE_DRIVER=file` and `SESSION_DRIVER=file` in `.env`

### Issue: Tailwind styles not loading
**Solution**: Make sure `npm run dev` is running in a separate terminal

### Issue: 404 on routes
**Solution**: Clear route cache with `php artisan route:clear`

## 📝 Next Steps

After setup, you can:

1. Customize the business settings in `config/kasse.php`
2. Update currency and tax settings in `.env`
3. Add more products via the Products page
4. Create additional categories
5. Test the POS system
6. Generate sales reports

## 🔒 Production Deployment

Before deploying to production:

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `npm run build` instead of `npm run dev`
6. Set up proper database backups
7. Configure SSL certificate
8. Set secure session cookies

## 📚 Project Structure

```
KasseControl/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── DashboardController.php
│   │   │   ├── PosController.php
│   │   │   ├── ProductController.php
│   │   │   ├── SaleController.php
│   │   │   └── PurchaseController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Sale.php
│   │   ├── Purchase.php
│   │   └── ...
│   └── Services/
│       ├── StockService.php
│       ├── SaleService.php
│       └── PurchaseService.php
├── database/
│   ├── migrations/
│   │   ├── 001_users.php
│   │   ├── 002_categories.php
│   │   └── ...
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── layouts/
│       ├── auth/
│       └── dashboard.blade.php
└── routes/
    ├── web.php
    ├── api.php
    └── auth.php
```

## 📧 Support

For issues or questions:
1. Check the error logs in `storage/logs/laravel.log`
2. Review the migration files for database structure
3. Check the routes in `routes/web.php`
4. Review service classes for business logic

---

**Project**: KasseControl - A simplified POS system
**Framework**: Laravel 10
**Frontend**: Tailwind CSS + Alpine.js
**Created**: November 2025
