# KasseControl - Simple POS System

A lightweight, modern Point of Sale (POS) system designed for small to medium businesses. Built with Laravel 10, focusing on simplicity, speed, and ease of use.

## 🎯 Project Philosophy

Unlike bloated POS systems, KasseControl focuses on what matters:
- **Simple & Fast** - No unnecessary features
- **Easy to Learn** - Intuitive interface for cashiers
- **Easy to Deploy** - Quick setup and installation
- **Clean Code** - Well-organized, maintainable codebase

## ✨ Core Features

### 🛒 Point of Sale
- Fast checkout interface
- Barcode scanning support
- Multiple payment methods (Cash, Card, Digital)
- Quick product search
- Receipt printing

### 📦 Inventory Management
- Simple product management
- Stock tracking
- Low stock alerts
- Categories organization
- Barcode generation

### 👥 Customer Management
- Customer database
- Purchase history
- Simple loyalty tracking

### 🔐 Role-Based Access (RBAC)
- Admin role
- Manager role
- Cashier role
- Custom permissions

### 📊 Simple Analytics
- Daily sales reports
- Top selling products
- Sales charts
- Inventory status

## 🛠 Tech Stack

- **Backend**: Laravel 10
- **Frontend**: Tailwind CSS + Alpine.js
- **Database**: MySQL (with mysqli driver)
- **Charts**: Chart.js
- **Barcode**: JsBarcode

## 📋 Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Node.js & NPM

## 🚀 Installation

```bash
# Clone repository
git clone https://github.com/yourusername/KasseControl.git
cd KasseControl

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env, then:
php artisan migrate --seed

# Build frontend
npm run build

# Start server
php artisan serve
```

## 🔑 Default Credentials

- **Admin**: admin@kasse.local / password
- **Manager**: manager@kasse.local / password
- **Cashier**: cashier@kasse.local / password

## 📁 Project Structure

```
KasseControl/
├── app/
│   ├── Http/Controllers/      # Controllers
│   ├── Models/                # Eloquent models
│   ├── Services/              # Business logic layer
│   └── Helpers/               # Helper functions
├── database/
│   ├── migrations/            # DB migrations (short names)
│   └── seeders/               # Sample data
├── resources/
│   ├── views/                 # Blade templates
│   └── js/                    # Alpine.js components
└── routes/
    ├── web.php               # Web routes
    └── api.php               # API routes
```

## 🎨 Design Principles

1. **Simplicity First** - Only essential features
2. **Performance** - Fast page loads, optimized queries
3. **User-Friendly** - Clean UI, easy navigation
4. **Maintainable** - Clean code, good documentation

## 📝 License

MIT License - feel free to use for your business!

## 🤝 Contributing

Contributions welcome! Please read our contributing guidelines.

## 💬 Support

For issues and questions, please use GitHub Issues.
## Installation Guide Updated
