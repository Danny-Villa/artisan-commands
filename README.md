# Davinet/Artisan-commands

This package provides a set of new artisan commands for Laravel

## Installation

Use the package manager [composer](https://getcomposer.org/) to install dannyvilla/artisan-commands

```bash
composer require dannyvilla/artisan-commands
```

## Usage

### View command
#### Generate an empty view 
```bash
php artisan make:view folder.subfolder.view
```

#### Generate a view with a layout
```bash
php artisan make:view folder.subfolder.view --layout=app
```

### Repository command
#### Generate an empty repository file
```bash
php artisan make:repository UserRepository
```
#### Generate a repository with a model
```bash
php artisan make:repository UserRepository --model=User
```
OR
```bash
php artisan make:repository UserRepository --model=App\Models\User
```

### Service command
#### Generate a serfvice class
```bash
php artisan make:service PayPalPaymentService
```

### Lang command
#### Generate a new locale file 
```bash
php artisan make:lang myFilanem --locale=es
```

#### Generate a new json locale file
```bash
php artisan make:lang --locale=es --json
```

### Class command
#### Generate a class
```bash
php artisan make:class App\Handlers\UserHandlers
```
or you can use a dot(.) as separator
```bash
php artisan make:class App.Handlers.UserHandlers --separator=.
```

#### Generate a trait 
```bash
php artisan make:class App\Traits\MyTrait --kind=trait
```

#### Generate an interface
```bash
php artisan make:class App\Contracts\IClassable --kind=interface
```

### File command
#### Generate a generic file 
```bash
php artisan make:file folder.subfolder1.subfolder2.filename --ext=php
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)
