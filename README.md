### Used Libraries

```
        "darkaonline/l5-swagger": "^8.0",  ---> API Documentation
        "laravel/passport": "^10.2", ---> for authentication
        "santigarcor/laratrust": "^6.3"  ---> for authorization with role, permission, and user
```

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

    php artisan laratrust:setup
    composer dump-autoload
    php artisan passport:install

    php artisan passport:client --personal
```

After creating your personal access client, place the client's ID and plain-text secret value in your application's .env file:

```

PASSPORT_PERSONAL_ACCESS_CLIENT_ID="client-id-value"
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET="unhashed-client-secret-value"

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


### Documenation Links

https://laravel.com/docs/8.x/passport
https://laratrust.santigarcor.me/docs/6.x/installation.html
https://blog.quickadminpanel.com/laravel-api-documentation-with-openapiswagger/



### Authorization Module

The authentication and authorization module has five (or six if you use teams feature) tables in the database:
•	User – stores user records.
•	roles — stores role records.
•	permissions — stores permission records.
•	teams — stores teams records (Only if you use the teams feature).
•	role_user — stores polymorphic relations between roles and users.
•	permission_role — stores many-to-many relations between roles and permissions.
•	permission_user — stores polymorphic relations between users and permissions.

```

$adminRole = Role::where('name', 'admin')->first();
$editUserPermission = Permission::where('name', 'edit-user')->first();
$user = User::find(1);

$user->attachRole($adminRole);
// Or
$user->attachRole('admin');

$user->attachPermission($editUserPermission);
// Or
$user->attachPermission('edit-user');


$user->isAbleTo('edit-user');

$user->hasRole('admin');
$user->isA('guide');
$user->isAn('admin');


```




## REST API with Passport Authentication

#### Step 1: Install Laravel

``laravel new project-name``  
or  
``composer create-project --prefer-dist laravel/laravel project-name``

#### Step 2: Database Configuration

Create a database and configure the env file.  

#### Step 3: Passport Installation

To get started, install Passport via the Composer package manager:

``composer require laravel/passport``

The Passport service provider registers its own database migration directory with the framework, so you should migrate your database after installing the package. The Passport migrations will create the tables your application needs to store clients and access tokens:

``php artisan migrate``

Next, you should run the `passport:install` command. This command will create the encryption keys needed to generate secure access tokens. In addition, the command will create "personal access" and "password grant" clients which will be used to generate access tokens:

``php artisan passport:install``

#### Step 4: Passport Configuration

After running the `passport:install` command, add the `Laravel\Passport\HasApiTokens` trait to your `App\Models\User` model. This trait will provide a few helper methods to your model which allow you to inspect the authenticated user's token and scopes:

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```

Next, you should call the `Passport::routes` method within the `boot` method of your `AuthServiceProvider`. This method will register the routes necessary to issue access tokens and revoke access tokens, clients, and personal access tokens:

```
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
```

Finally, in your `config/auth.php` configuration file, you should set the `driver` option of the `api` authentication guard to `passport`. This will instruct your application to use Passport's `TokenGuard` when authenticating incoming API requests:

```
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],
```


#### Step 6: Create Controller Files

in next step, now we have created a new controller as LoginController and PostController:

``php artisan make:controller Api/LoginController``


#### Step 7: Create API Routes
In this step, we will create api routes. Laravel provide api.php file for write web services route. So, let's add new route on that file.

**routes/api.php**

```
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [LoginController::class, 'register']);
});
```

#### Step 8: Create Helper Functions

**app/Helpers/Functions.php**

```
<?php
   
   /**
    * Success response method
    *
    * @param $result
    * @param $message
    * @return \Illuminate\Http\JsonResponse
    */
   function sendResponse($result, $message)
   {
       $response = [
           'success' => true,
           'data'    => $result,
           'message' => $message,
       ];
   
       return response()->json($response, 200);
   }
   
   /**
    * Return error response
    *
    * @param       $error
    * @param array $errorMessages
    * @param int   $code
    * @return \Illuminate\Http\JsonResponse
    */
   function sendError($error, $errorMessages = [], $code = 404)
   {
       $response = [
           'success' => false,
           'message' => $error,
       ];
   
       !empty($errorMessages) ? $response['data'] = $errorMessages : null;
   
       return response()->json($response, $code);
   }
```

**composer.json**
```
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        },
        "files": [
            "app/Helpers/Functions.php"
        ]
    },
```

``composer dump-autoload``

**app\Http\Controllers\Api\LoginController.php**

```
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class LoginController extends Controller
{
    /**
     * User login API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user             = Auth::user();
            $success['name']  = $user->name;
            $success['token'] = $user->createToken('accessToken')->accessToken;

            return sendResponse($success, 'You are successfully logged in.');
        } else {
            return sendError('Unauthorised', ['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * User registration API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $success['name']  = $user->name;
            $message          = 'Yay! A user has been successfully created.';
            $success['token'] = $user->createToken('accessToken')->accessToken;
        } catch (Exception $e) {
            $success['token'] = [];
            $message          = 'Oops! Unable to create a new user.';
        }

        return sendResponse($success, $message);
    }
}
```


Now we are ready to run full restful api and also passport api in laravel. so let's run our example so run bellow command for quick run:

``php artisan serve``

make sure in details api we will use following headers as listed bellow:

```
'headers' => [
    'Accept'        => 'application/json',
    'Authorization' => 'Bearer '.$accessToken,
]
```
