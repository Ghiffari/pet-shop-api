# Pet Shop API
> The purpose of this project is for Buckhill Technical Assesment

## Installation

- Clone this project
- `composer install`
- `cp .env.example .env`
- `php artisan key:generate`
- Set `.env` files with your database credentials, pub/pri key pairs and stripe credentials
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
JWT_PUBLIC_KEY_PATH="path/to/pub-key"
JWT_PRIVATE_KEY_PATH="path/to/pri-key"
STRIPE_KEY="your-stripe-key"
STRIPE_SECRET="your-stripe-secret"
```
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan serve`

## API Documentation
Once installed can be accessed on `{base_url}/api/documentation`

## Tests

`php artisan test`
