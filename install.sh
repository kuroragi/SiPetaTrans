#!/bin/bash

# SIPETA-TRANS Installation Script
# This script automates the setup of SIPETA-TRANS project

set -e

PROJECT_DIR="/Users/rahmadsyahmulya/Aplikasi/Sipeta-Trans"
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${BLUE}============================================${NC}"
echo -e "${BLUE}    SIPETA-TRANS Installation Script${NC}"
echo -e "${BLUE}============================================${NC}"
echo ""

# Check prerequisites
echo -e "${YELLOW}[1/8] Checking prerequisites...${NC}"
if ! command -v php &> /dev/null; then
    echo "PHP not found. Please install PHP 8.3+"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    echo "Composer not found. Please install Composer"
    exit 1
fi

if ! command -v node &> /dev/null; then
    echo "Node.js not found. Please install Node.js 18+"
    exit 1
fi

echo -e "${GREEN}✓ Prerequisites OK${NC}"
echo ""

# Navigate to project directory
cd "$PROJECT_DIR"
echo -e "${YELLOW}[2/8] Installing Composer dependencies...${NC}"
composer install
echo -e "${GREEN}✓ Composer dependencies installed${NC}"
echo ""

# Install npm dependencies
echo -e "${YELLOW}[3/8] Installing npm dependencies...${NC}"
npm install
echo -e "${GREEN}✓ npm dependencies installed${NC}"
echo ""

# Check .env file
echo -e "${YELLOW}[4/8] Checking .env configuration...${NC}"
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "Created .env from .env.example"
fi
echo -e "${GREEN}✓ .env file ready${NC}"
echo ""

# Generate app key if needed
echo -e "${YELLOW}[5/8] Generating application key...${NC}"
php artisan key:generate --force
echo -e "${GREEN}✓ Application key generated${NC}"
echo ""

# Run migrations
echo -e "${YELLOW}[6/8] Running database migrations...${NC}"
php artisan migrate --force
echo -e "${GREEN}✓ Migrations completed${NC}"
echo ""

# Seed database
echo -e "${YELLOW}[7/8] Seeding database with sample data...${NC}"
php artisan db:seed --force
echo -e "${GREEN}✓ Database seeded${NC}"
echo ""

# Build frontend assets
echo -e "${YELLOW}[8/8] Building frontend assets...${NC}"
npm run build
echo -e "${GREEN}✓ Frontend assets built${NC}"
echo ""

echo -e "${GREEN}============================================${NC}"
echo -e "${GREEN}    Installation Completed Successfully!${NC}"
echo -e "${GREEN}============================================${NC}"
echo ""
echo -e "${BLUE}Next steps:${NC}"
echo "1. Start the development server:"
echo -e "   ${YELLOW}php artisan serve${NC}"
echo ""
echo "2. Open your browser:"
echo -e "   ${YELLOW}http://127.0.0.1:8000${NC}"
echo ""
echo "3. Login credentials:"
echo -e "   Email: ${YELLOW}admin@sipeta.com${NC}"
echo ""
echo -e "${BLUE}Documentation:${NC}"
echo -e "  - Setup Guide: ${YELLOW}README.md${NC}"
echo -e "  - Development: ${YELLOW}DEVELOPMENT.md${NC}"
echo ""
