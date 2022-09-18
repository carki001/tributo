## TRIBUTO

Simple app with customizable header, title, section, section gallery and footer. The app uses laravel 9 and Vue 2.

To make it work you just need to do the following:

-   Run composer install
-   Run npm install
-   Configure the .env file
-   Run php artisan migrate
-   Run php artisan db:seed --class=UserSeeder
-   Run php artisan db:seed --class=SettingSeeder
-   Run php artisan passport:install
-   Run npm run dev
-   Go to /admin, then log in (test@mail.com, password)
-   Change user and password for the admin user
-   Custom the app to your liking
