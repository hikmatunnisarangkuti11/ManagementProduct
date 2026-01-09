1. git clone https://github.com/hikmatunnisarangkuti11/ManagementProduct.git
2. cd ManagementProduct
3. composer install
4. rename .env.example menjadi .env
5. php artisan key:generate
6. rename env : 
DB_DATABASE=nama_db
DB_USERNAME=root
DB_PASSWORD=
7. php artisan migrate (run in terminal)
8. php artisan serve (run in terminal)
