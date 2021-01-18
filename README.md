# pdo

1. Create a `.env` file with the environment variable listed in `.env.example`.
2. Copy your database credentials from your Heroku Postgres database into the `PDO_CONNECTION_STRING` environment variable in `.env` following this format: `pgsql:<Host>;port=5432;dbname=<Database>;user=<User>;password=<Password>`.
3. `composer install`
4. `php -S localhost:8000`
5. Visit http://localhost:8000
