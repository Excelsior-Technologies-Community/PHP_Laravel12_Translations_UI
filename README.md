# PHP_Laravel12_Translations_UI


## Project Description

PHP_Laravel12_Translations_UI is a Laravel 12 based web application that provides a user-friendly interface to manage multi-language translations dynamically. This project allows users or administrators to add new translation keys and update translation values for multiple languages such as English, French, and Spanish through a simple dashboard.

The application stores translations in a MySQL database and retrieves them dynamically, making it easy to manage language content without modifying language files manually. It uses Laravel’s MVC architecture, including Model, View, and Controller, to handle translation logic, display data, and interact with the database efficiently.

This project also includes middleware to automatically set the application language based on the user’s selected locale stored in session. It improves usability by allowing real-time language switching and centralized translation management.

The main objective of this project is to demonstrate Laravel’s localization system, database integration, middleware usage, and dynamic UI development for managing multilingual applications.


## Key Features

- Add new translation keys dynamically

- Edit translations for multiple languages

- Store translations in database

- Automatic language switching using middleware

- User-friendly Translation UI dashboard

- Built using Laravel 12 MVC architecture

- Supports multi-language applications



## Technologies Used

- PHP 8
- Laravel 12
- MySQL
- Blade Template Engine
- HTML, CSS, JavaScript
- Vite
- Composer

---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP\_Laravel12\_Translations\_UI "12.\*"

```

### Go inside project:

```
cd PHP\_Laravel12\_Translations\_UI

```

#### Explanation:

- This command installs a fresh Laravel 12 application.

- composer → PHP dependency manager

- create-project → creates new Laravel project

- PHP_Laravel12_Translations_UI → project folder name

- "12.*" → installs Laravel version 12





## STEP 2: Database Setup

### Open .env and set:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_translations_ui
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```

Database name: laravel12_translations_ui


```

#### Explanation:

Laravel uses this configuration to connect database.




## STEP 3: Install Laravel Translations Package

### Run:

```
composer require outhebox/laravel-translations --with-all-dependencies

```

#### Explanation:

- This installs Laravel Translations package.

- Purpose of package:

- Provides translation UI

- Manages translations easily

- Supports multiple languages

- Stores translations in database





## STEP 4: Publish Package Assets, Config, and Migrations

### Run:

```
php artisan translations:install

```

### Run Migrations

```
php artisan migrate

```

#### Explanation:

This command publishes:

- Config file

- Migration file

- Assets required for translations





## STEP 5: Set Up Authentication (Optional but recommended)

### We’ll use Laravel Breeze for auth:

```
composer require laravel/breeze --dev

php artisan breeze:install

npm install

npm run dev

php artisan migrate

```




## STEP 6: Configure Translations

### config/translations.php:

```
<?php

return [
    'default_locale' => 'en',
    'locales' => ['en', 'fr', 'es'], // Supported languages
    'ui_path' => 'translations',     // URL for dashboard
    'middleware' => ['auth'],        // Protect dashboard
];

```

#### Explanation:

This config defines:

- default_locale → default language (English)

- locales → supported languages:

- en → English

- fr → French

- es → Spanish

- ui_path → URL path for translations dashboard





## STEP 7: Add Translations Routes

### Open routes/web.php and add:

```

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\TranslationController;

/*
|--------------------------------------------------------------------------
| Language Switch Route
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', function ($locale) {

    if (in_array($locale, ['en', 'fr', 'es'])) {
        Session::put('locale', $locale);
    }

    return redirect()->back();

})->name('lang.switch');


/*
|--------------------------------------------------------------------------
| Translations Dashboard Route
|--------------------------------------------------------------------------
*/



Route::get('/translations', [TranslationController::class,'index']);
Route::post('/translations/save', [TranslationController::class,'store']);
Route::post('/translations/add', [TranslationController::class,'addKey']);

/*
|--------------------------------------------------------------------------
| Home Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


```

#### Explanation:

Routes define application URLs.




## STEP 8: Add Translation Files

### English – resources/lang/en/messages.php

```
<?php
return [
    'title' => 'Laravel Translations UI',
    'welcome' => 'Welcome to Laravel Translations UI!',
];

```

### French – resources/lang/fr/messages.php

```
<?php
return [
    'title' => 'Interface de Traductions Laravel',
    'welcome' => 'Bienvenue à l’interface de traductions Laravel!',
];

```

### Spanish – resources/lang/es/messages.php

```
<?php
return [
    'title' => 'Interfaz de Traducciones Laravel',
    'welcome' => '¡Bienvenido a la interfaz de traducciones Laravel!',
];

```

#### Explanation:

These files store translation text.

Laravel uses these files to display content in selected language.




## STEP 9: Optional Middleware for Dynamic Locale

### Create app/Http/Middleware/SetLocale.php:

```
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        return $next($request);
    }
}

```

#### Explanation:

When user selects language:

Language saved in session

Middleware sets language automatically






## STEP 10: Register middleware

### Open: bootstrap/app.php

#### Add:

```
->withMiddleware(function ($middleware) {
    $middleware->append(\App\Http\Middleware\SetLocale::class);
})

```

#### Explanation:

This step registers the SetLocale middleware so Laravel automatically sets the selected language from session on every request. 

It ensures the application displays content in the user-selected locale.





## STEP 11: Replace Migration

### Open: database/migrations/xxxx_create_translations_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale');
            $table->string('group')->default('messages');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['locale', 'group', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};

```

### Run:

```
php artisan migrate

```

#### Explanation:

This step creates the translations table in the database to store translation keys, languages (locale), and values. 

Migration helps manage database structure using PHP code.





## STEP 12: Create Model

### Run:

```
php artisan make:model Translation

```

### Open: app/Models/Translation.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = [
        'locale',
        'group',
        'key',
        'value'
    ];
}

```

#### Explanation:

The Translation model is used to interact with the translations table in the database. 

It allows inserting, updating, deleting, and fetching translation records using Eloquent ORM.






## STEP 13: Create Controller

### Run:

```
php artisan make:controller TranslationController

```

### Open: app/Http/Controllers/TranslationController.php

```

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Translation;

class TranslationController extends Controller
{
    private $locales = ['en', 'fr', 'es'];

    public function index()
    {
        $translations = Translation::all()
            ->groupBy('key');

        return view('translations.index', [
            'translations' => $translations,
            'locales' => $this->locales
        ]);
    }

    public function store(Request $request)
    {
        foreach ($request->translations as $key => $locales) {

            foreach ($locales as $locale => $value) {

                Translation::updateOrCreate(
                    [
                        'key' => $key,
                        'locale' => $locale,
                        'group' => 'messages'
                    ],
                    [
                        'value' => $value
                    ]
                );
            }
        }

        return back()->with('success', 'Translations saved successfully');
    }

    public function addKey(Request $request)
    {
        foreach ($this->locales as $locale) {

            Translation::create([
                'locale' => $locale,
                'group' => 'messages',
                'key' => $request->key,
                'value' => ''
            ]);
        }

        return back();
    }
}

```


#### Explanation:

The TranslationController handles translation logic such as displaying translations, saving updated translations, and adding new keys.

It connects the Model and View.





## STEP 14: Create View Folder

### Create folder:

```
resources/views/translations

```

### Create file: resources/views/translations/index.blade.php

```
<!DOCTYPE html>
<html>
<head>

<title>Translations UI</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
padding:40px;
}

.card{
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

table{
width:100%;
border-collapse:collapse;
}

th,td{
padding:10px;
border:1px solid #ddd;
}

input{
width:100%;
padding:6px;
}

button{
padding:8px 15px;
background:#28a745;
color:white;
border:none;
border-radius:5px;
}

.add-btn{
background:#007bff;
}

</style>

</head>
<body>

<div class="card">

<h2>Laravel Translations UI</h2>

@if(session('success'))
<p style="color:green">{{session('success')}}</p>
@endif


<form method="POST" action="/translations/add">

@csrf

<input type="text" name="key" placeholder="New Key" required>

<button class="add-btn">Add Key</button>

</form>


<br>


<form method="POST" action="/translations/save">

@csrf

<table>

<tr>

<th>Key</th>

@foreach($locales as $locale)

<th>{{ strtoupper($locale) }}</th>

@endforeach

</tr>


@foreach($translations as $key=>$items)

<tr>

<td>{{ $key }}</td>

@foreach($locales as $locale)

<td>

<input type="text"
name="translations[{{$key}}][{{$locale}}]"
value="{{ optional($items->where('locale',$locale)->first())->value }}">

</td>

@endforeach

</tr>

@endforeach


</table>


<br>

<button type="submit">Save Translations</button>

</form>

</div>

</body>
</html>

```

#### Explanation:

This step creates the Translation UI where users can add keys and edit translations for different languages. 

It displays data in a table format and sends updates to the controller.





## STEP 15: Test

### Run the server:

```
php artisan serve

```

### Visit:

```
http://127.0.0.1:8000

```

#### Explanation:

This step runs the Laravel server and opens the project in the browser to verify the Translation UI works correctly. 

It confirms translations can be added and saved successfully




## So you can see this type Output:


### Translation Dashboard:


<img width="1919" height="949" alt="Screenshot 2026-02-26 111945" src="https://github.com/user-attachments/assets/8634d91e-28e1-4ab2-9025-395cf03c0fbb" />


### Add Key:


<img width="1917" height="870" alt="Screenshot 2026-02-26 112427" src="https://github.com/user-attachments/assets/1284ae4a-eedc-42fb-949f-f66c9544fe5a" />


### Save Translations:


<img width="1919" height="899" alt="Screenshot 2026-02-26 112525" src="https://github.com/user-attachments/assets/797533d8-622e-4264-825c-01db00f87e9c" />



---

# Project Folder Structure:

```
PHP_Laravel12_Translations_UI/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── TranslationController.php
│   │   │
│   │   └── Middleware/
│   │       └── SetLocale.php
│   │
│   ├── Models/
│   │   └── Translation.php
│   │
│   └── Providers/
│       └── AppServiceProvider.php
│
├── bootstrap/
│   ├── app.php
│   └── cache/
│
├── config/
│   ├── app.php
│   ├── database.php
│   └── translations.php     (if using package config)
│
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── xxxx_xx_xx_create_users_table.php
│   │   └── xxxx_xx_xx_create_translations_table.php
│   │
│   └── seeders/
│
├── public/
│   ├── index.php
│   └── favicon.ico
│
├── resources/
│   ├── lang/
│   │   ├── en/
│   │   │   └── messages.php
│   │   │
│   │   ├── fr/
│   │   │   └── messages.php
│   │   │
│   │   └── es/
│   │       └── messages.php
│   │
│   └── views/
│       ├── welcome.blade.php
│       │
│       └── translations/
│           └── index.blade.php
│
├── routes/
│   ├── web.php
│   └── console.php
│
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
│
├── tests/
│
├── vendor/
│
├── .env
├── .env.example
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── README.md
└── vite.config.js

```
