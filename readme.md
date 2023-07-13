# How to run tests?

1. Start docker on your machine
2. Run `docker-compose up -d` in the root directory of the project
3. Run `docker-compose exec php composer install`
4. Run `docker-compose exec composer prepare-test-db`
5. Run `docker-compose exec composer tests`
