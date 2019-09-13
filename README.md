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
```console
php artisan make:repository User
```
#### Generate a repository with a model
```console
php artisan make:repository --model=User
```

### View command
#### Generate a empty view 
```console
php artisan make:view folder.subfolder.view
```

#### Generate a view with a layout
```console
php artisan make:view folder.subfolder.view --layout=app
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)
