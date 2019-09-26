## Simple Webhook Server by Lumen 6.0

## Requirements 
  * PHP 7.2 +
  * MySQL 5.7 +
  * Composer
  * Library Dependencies :
    * Guzzle 6.3 +
    
## Up and running
 * install dependencies by composer command :
   `composer install`
 * Set the database config if needs to store the data located in .env file
  (.env file ignore by ignore file, copy the env.example and paste .env)
 * In the root folder Run teh project in specific post 9876 by
   `php -S localhost:9876 -t public`
   
## Run tests
 * run `vendor\bin\phpunit`
 file phpunit.xml can be configure test env, by default set in memory.
 * To use the persistent data model run the following commands command 
 `php artisan database migrate` (create tables located in ase\migrations folder)
 `php artisan db:seed` (to create some fake data to test db located in database\seeds folder)
 

 
