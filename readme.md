# Questionnaire App

## Project Scope
- An API that returns the questionnaire, which includes all the questions and answers (plus any conditional restrictions for when questions should appear). Frontend engineers will use this API to render the complete questionnaire flow to the customer.
- An API that accepts the questionnaire answers and returns an array of the recommended products that the customer can purchase.
- A simple admin panel for the medical team to be able to input additional questions or alter the existing questions/recommendation logic.

## How to run
1. Clone this repository
2. Under the `.env` file change the database connection 
3. Install composer `composer install`
4. Make sure you are connected to your database in order to sync migration and run the following commands
5. `php bin/console doctrine:database:create` to create database schema
6. `php bin/console doctrine:migrations:migrate` for database migration
7. `php bin/console doctrine:fixtures:load` to populate data by running the DataFixtures
8. `symfony serve:start` to boot up localhost server and access homepage via http://localhost:8000
9. Test the app with different endpoints, please refer to `routs.yaml` for all the routes