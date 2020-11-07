### Install
- `composer install` \ `composer update`

### Publish all vendors
- `php artisan vendor:publish --provider="Dingo\Api\Provider\LaravelServiceProvider"`
- `php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"`

### Show all routes
- `php artisan api:routes`

### Generate JWT secret 
- `php artisan jwt:secret`

### Create new API version
- `config/auth.php`
- create new `providers`
- create new `guards`
- add new `guard` name to `defaults['guard']`
- create new folder in `routes/api`
- add to `RouteServiceProvider` new path to routes folder & namespace
- add new folder in `app/Api`

### Run tests
- copy `.env.example` to `.env.testing`
- change connection to database
- run `vendor/bin/phpunit tests/Feature/UsersTest.php`