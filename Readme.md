# Resource Controller

## Description
This package helps to simplify the creation of api resource controllers in Laravel.

## Installation
Require resource controller via 
```
composer require reggiebeatz71/resource-controller
```

## Usage

### Step 1
Create a new controller
```
php artisan make:controller TestController
```

### Step 2
Change the controller's default class inheritance form Resource Controller

```php
namespace App\Http\Controllers;

use Reggiebeatz71\ResourceController\ResourceController;

use Illuminate\Http\Request;

class TestController extends ResourceController
```

### Step 3 (Almost done)
Implement the model, storeRules and updateRules methods in the controller

```php
    protected function model () {
        return // Model class goes here;
    }

    protected function storeRules () {
        return [
            // laravel validatioin rules goes here
        ];
    }

    protected function updateRules () {
        return [
            // laravel validatioin rules goes here
        ];
    }
```

### Step 4 (Finishing touch)
Add a resource route in your api route

```php
Route::apiResource('test', 'TestController');
```
