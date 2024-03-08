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

The Docker build process will run `composer inst2all` and provide a functional `vendor` directory and autoload file to the container. To have access to these for local development, you may run composer within the container to force it to sync back to the host environment, you may use the container's shell to run composer.

```bash
docker compose exec basket-php composer install
```