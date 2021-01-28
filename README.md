# Project Setup
## Download the back-end repo using git
```
git clone https://github.com/imhanifsheikh/crud_products_back_end.git
```
###### CD into directory
```
cd crud_products_back_end\
```
###### Install required packages
```
composer install
```
## Database migration
###### Create .env file 
```
cp .env.example .env
```
**Before run the migration update DB_USERNAME, DB_PASSWORD & DB_DATABASE in .env file**
###### Generate App Key 
```
php artisan key:generate
```
###### Run migrate command 
```
php artisan migrate
```
###### Create symbolic link of storage To public folder
###### To store & view images create a symbolic link of storage directory in public directory 
```
php artisan storage:link
```
###### Generate JWT secret
```
php artisan jwt:secret
```
## Run the application
```
php artisan serve
```

