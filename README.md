## Project setup

- copy `.env.example` file to `.env` file
- add values for `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `WEATHER_KEY` in your `.env` file
- run `composer install`
- run `php artisan migrate`
- run `php artisan serve`
- run `php artisan test` for unit test

- **[Note!](your should have ssl installed for your domain your you can set verify false for CURL requests for test purpose)**
