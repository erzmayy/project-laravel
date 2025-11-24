REM 1. Clone project
git clone https://github.com/erzmayy/project-laravel.git

REM 2. Masuk folder
cd project-laravel

REM 3. Install dependency
composer install
npm install
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run build

REM 4. Copy .env
copy .env.example .env

REM 5. Generate key
php artisan key:generate

php artisan storage:link
php artisan migrate:fresh --seed


REM 6. (Optional) Edit DB di file .env
REM DB_DATABASE=project_laravel
REM DB_USERNAME=root
REM DB_PASSWORD=

REM 7. Migrasi
php artisan migrate

REM 8. Jalankan Laravel
npm run dev/php artisan serve
# SELESAI! Buka browser:
# Frontend: http://thrift-shop.test
# Admin: http://thrift-shop.test/admin/dashboard
# 
# Login Admin:
# Email: admin@thriftshop.com
# Password: password
# 
# Login Customer:
# Email: customer@thriftshop.com
# Password: password
