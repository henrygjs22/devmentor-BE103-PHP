# Installation && Setup
1. Clone the repository and into to directory.
   ```sh
   git clone git@github.com:henrygjs22/devmentor-BE103-PHP.git henry-BE103-PHP && cd henry-BE103-PHP
   ```

2. Install Composer vendor directory and [Laravel Sail](https://laravel.com/docs/10.x/sail). This step uses Laravel Sail PHP 8.2 image with preinstalled Composer to install all dependencies.
   ```sh
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php82-composer:latest \
       composer install --ignore-platform-reqs && cp -n .env.example .env
   ```

3. Start the container and enerate application key (via Laravel Sail).
   ```sh
   vendor/bin/sail up -d && vendor/bin/sail artisan key:generate
   ```

Once the application's containers have been started, you may access the project in your web browser at: http://localhost.
   > For more information, view the document for [Laravel Sail](https://laravel.com/docs/10.x/sail) and Docker.

# Make migration and seed
1. Modify .env file to match your local db configuration.
```
...
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=db_name
DB_USERNAME=db_user
DB_OASSWIRD=db_password
...
```
2. Up whole docker containers by using command: sail up -d
3. Apply current migrations to local db by using: sail artisan migrate

# API documentation
Here is the [API documentation]() for this project.

# System Design
[System Design](public/images/system_design.png)

# DB ER-model
[Database ER Model](public/images/db_er_model.png)
