# code-test-basket

## Gettings Started

1. Ensure you have Docker installed and running
2. Run `docker compose` to build the container

    ```bash
    docker compose up -d --build
    ```

3. Run tests

    ```bash
    docker compose exec basket-php vendor/bin/phpunit
    ```

## Local Development

### Intellisense for Composer Depenencies

The Docker build process will run `composer install` and provide a functional `vendor` directory and autoload file to the container. To have access to these for local development, you may run composer within the container to force it to sync back to the host environment, you may use the container's shell to run composer.

```bash
docker compose exec basket-php composer install
```

### Code Style Checking

You can check the code style against the PSR-12 standard with the following:

```bash
docker compose exec basket-php vendor/bin/phpcs --standard=PSR12 src
docker compose exec basket-php vendor/bin/phpcs --standard=PSR12 tests
```

### Static Analysis

Running static analysis to check for various kinds of bugs:

```bash
docker compose exec basket-php vendor/bin/phpstan analyze -l 9 src tests
```
