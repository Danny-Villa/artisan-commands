# Davinet/Artisan-commands

This package provides a set of new artisan commands for Laravel

## Installation

Use the package manager [composer](https://getcomposer.org/) to install Simtoken.

```bash
composer require dannyvilla/artisan-commands
```

## Usage

### Repository command
#### Generate an empty repository file
```bash
php artisan make:repository UserRepository
```
#### Generate a repository with a model
```bash
php artisan make:repository UserRepository --model=User
```

### View command
#### Generate an empty view 
```bash
php artisan make:view folder.subfolder.view
```

#### Generate a view with a layout
```bash
php artisan make:view folder.subfolder.view --layout=app
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)
