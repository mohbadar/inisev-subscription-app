### Clone the Repository

```
    git clone https://github.com/mohbadar/inisev-subscription-app
```


#### Install Dependencies

```
    composer install --optimize-autoloader 
```


#### Generate Keys

```
    php artisan key:generate
```


#### Generate API Documentation 

```
    php artisan l5-swagger:generate
```

Refer to http:://localhost:8000/api/documentation


#### Execute Migrations and Tables Seeders

```
    php artisan migrate
    php artisan db:seed --class=RolesTableSeeder
    php artisan db:seed --class=WebsitesTableSeeder
```



#### Launch Application

```
    php artisan serve
```
