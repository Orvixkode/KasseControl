#!/bin/bash

# KasseControl Quick Setup Script
# This script will help you set up the application quickly

echo "╔═══════════════════════════════════════╗"
echo "║   KasseControl - Quick Setup Script   ║"
echo "╚═══════════════════════════════════════╝"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: artisan file not found. Please run this script from the KasseControl directory.${NC}"
    exit 1
fi

echo -e "${YELLOW}Step 1: Checking .env file...${NC}"
if [ ! -f ".env" ]; then
    echo -e "${YELLOW}Creating .env file from .env.example...${NC}"
    cp .env.example .env
    echo -e "${GREEN}✓ .env file created${NC}"
else
    echo -e "${GREEN}✓ .env file already exists${NC}"
fi

echo ""
echo -e "${YELLOW}Step 2: Setting up database credentials...${NC}"
read -p "Enter MySQL username [root]: " db_user
db_user=${db_user:-root}

read -sp "Enter MySQL password: " db_pass
echo ""

read -p "Enter database name [kasse_pos]: " db_name
db_name=${db_name:-kasse_pos}

# Update .env file
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$db_user/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$db_pass/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$db_name/" .env

echo -e "${GREEN}✓ Database credentials updated in .env${NC}"

echo ""
echo -e "${YELLOW}Step 3: Creating database...${NC}"
mysql -u "$db_user" -p"$db_pass" -e "CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Database created successfully${NC}"
else
    echo -e "${RED}✗ Failed to create database. Please create it manually.${NC}"
    echo -e "${YELLOW}  Run: mysql -u $db_user -p${NC}"
    echo -e "${YELLOW}  Then: CREATE DATABASE $db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;${NC}"
    read -p "Press Enter after creating the database manually..."
fi

echo ""
echo -e "${YELLOW}Step 4: Generating application key...${NC}"
php artisan key:generate --force
echo -e "${GREEN}✓ Application key generated${NC}"

echo ""
echo -e "${YELLOW}Step 5: Setting up storage permissions...${NC}"
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✓ Permissions set${NC}"

echo ""
echo -e "${YELLOW}Step 6: Running migrations and seeders...${NC}"
php artisan migrate --seed --force

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Database migrated and seeded successfully${NC}"
else
    echo -e "${RED}✗ Migration failed. Please check your database credentials.${NC}"
    exit 1
fi

echo ""
echo -e "${GREEN}╔═══════════════════════════════════════╗${NC}"
echo -e "${GREEN}║     Setup completed successfully!     ║${NC}"
echo -e "${GREEN}╚═══════════════════════════════════════╝${NC}"
echo ""
echo -e "${YELLOW}Default Login Credentials:${NC}"
echo "┌────────────────────────────────────────┐"
echo "│ Admin:   admin@kasse.local / password  │"
echo "│ Manager: manager@kasse.local / password│"
echo "│ Cashier: cashier@kasse.local / password│"
echo "└────────────────────────────────────────┘"
echo ""
echo -e "${YELLOW}To start the application:${NC}"
echo ""
echo -e "${GREEN}Terminal 1 (Laravel):${NC}"
echo "  php artisan serve"
echo ""
echo -e "${GREEN}Terminal 2 (Frontend):${NC}"
echo "  npm run dev"
echo ""
echo -e "${GREEN}Then visit:${NC} http://localhost:8000"
echo ""
