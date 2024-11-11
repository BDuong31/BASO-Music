## BASO-Music
## Installation Guide
**On Windows:**
cd C\xampp\htdocs

**On MacOS :**
cd /Applications/XAMPP/htdocs

**Clone the project :**
git clone https://github.com/BDuong31/BASO-Music.git

## Install Dependencies
cd BASO-Music

composer install

## Create .env file from .env.example template
cp .env.example .env

## Set Up Database and Run Migrations
**Run migrations:**
php artisan migrate

**Seed sample data into the database:**
php artisan db:seed

**Drop and re-run migrations with seeding:**
php artisan migrate:fresh --seed

## Run the Project
php -S localhost:8000 -t public
