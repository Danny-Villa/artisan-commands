# Davinet/Artisan-commands

This package provides a set of new artisan commands for Laravel

## Installation

Use the package manager [composer](https://getcomposer.org/) to install Simtoken.

```bash
composer require dannyvilla/artisan-commands
```

## Usage

### Repository command
#### Generate a empty repository file
```bash
php artisan make:repository User
```
#### Generate a repository with a model
```bash
php artisan make:repository --model=User
```

### View command
#### Generate a empty view 
```bash
php artisan make:view folder.subfolder.view
```

#### Generate a view with a layout
```bash
php artisan make:view folder.subfolder.view --layout=app
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

### File command
#### Generate a generic file 
```bash
php artisan make:file folder.subfolder1.subfolder2.filename --ext=php
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)
