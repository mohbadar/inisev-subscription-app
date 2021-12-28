### Used Libraries

```
        "darkaonline/l5-swagger": "^8.0",  ---> API Documentation
        "laravel/passport": "^10.2", ---> for authentication
        "santigarcor/laratrust": "^6.3"  ---> for authorization with role, permission, and user
```

### Note

- To Send Email the Mail Server Configuration Needs to be Managed

### MUST:-
- Use PHP 7.* --`Done`
- Write migrations for the required tables.  -- `Done`
- Endpoint to create a "post" for a "particular website". -- `Done`
- Endpoint to make a user subscribe to a "particular website" with all the tiny validations included in it. --`Done`
- Use of command to send email to the subscribers. -- `Done`
- Use of queues to schedule sending in background. -- `Done`
- No duplicate stories should get sent to subscribers. --`Done`
- Deploy the code on a public github repository. -- `Done`

### OPTIONAL:-
- Seeded data of the websites. --`Done`
- Open API documentation (or) Postman collection demonstrating available APIs & their usage. -- `Done` 
- Use of latest laravel version. --`Done`
- Use of contracts & services.  -- `Done`
- Use of caching wherever applicable. -- `Done`
- Use of events/listeners. -- `Done`





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
