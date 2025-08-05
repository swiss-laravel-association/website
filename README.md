## Swiss Laravel Association Website

Welcome to the Swiss Laravel Association website repository! This project is built using Laravel and serves as the official website for the Swiss Laravel Association. It is designed to provide information about the association, its events, and resources for the Laravel community in Switzerland.

## Requirements

- PHP 8.2 or higher
- Composer (for PHP dependencies)
- Node.js (for frontend assets)
- MySQL or SQLite (for database)

## Installation

1. Clone the repository: `git clone git@github.com:swiss-laravel-association/website.git`
2. Install PHP dependencies: `composer install`
3. Install Node.js dependencies: `npm ci`
4. Set up the environment file: `cp .env.example .env`
   - Update the `.env` file with your database credentials and other environment settings.
   - Ensure you have the correct `APP_URL` set for your local environment.
   - If using SQLite, ensure the database file is writable.
5. Generate application key: `php artisan key:generate`
6. Run migrations and seed the database: `php artisan migrate --seed`
7. Compile frontend assets: `npm run dev` or `npm run prod` for production builds.

### Development


## Linting and Refactoring

We use Laravel Pint to fix code style issues. To run Pint, you can use the following command:

```bash
composer refactor:lint
```

We also use Rector to refactor the codebase. To run Rector, you can use the following command:

```bash
composer refactor:refactor
```

You can also run all refactoring tasks together with the following command:

```bash
composer refactor
```

## Tests

We use Pest for testing. To run the tests, you can use the following command:

```bash
composer run test:pest
```

You can also run the entire test suite, including Linting, Refactoring, Type Checking, and Pest tests, with the following command:

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities
Please review [our security policy](https://github.com/swiss-laravel-association/website/security/policy) on how to report security vulnerabilities.
