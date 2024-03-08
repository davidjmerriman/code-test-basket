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

## Implementation Details

Some of the assumptions and implementation details about this project:

* There is no user interface; all functionality of the `Basket` can be verified through unit tests and `BasketEndToEndTest`. The latter implements the specific test scenarios listed in the project requirements.
* The delivery cost is being calculated after the any discount offers are applied; this gives the correct values for the test scenarios in the project requirements. However, this could easily be changed or even made configurable without much effort.
* The Provider interfaces were designed to allow offers, product catalogs, delivery service rules, etc. to come from a database, a web API, a configuration file, etc., and dropped in via dependency injection, especially using an automatic composition tool like what Laravel ships with. It also allows easy mocking for unit testing, as seen in the `Basket` unit tests. The primary benefit is the ability to develop new providers side by side with the existing ones, and use feature flags to turn them on and off in production with little risk.
* The IOffer interface was designed to follow the Strategy pattern, which is why there are unused parameters when it comes to implementing the `BuyXGetYPercent` concrete offer. It is conceivable that other offer types might have different logic depending on more than just the line items, but might need access to the whole catalog to determine a discount.
* The Docker container does not have an entry point or a daemon service to run, so it has been configured with a TTY so that it stays open. This would be changed once this grows into an API or a web application.
* The TieredDelivery class does not yet allow initialization in the constructor like the other providers (`StaticOffers`, `StaticCatalog`), and must be set up using `TieredDelivery::addTier(...)` at the moment. Given more time, I would create classes and validation to allow setting up all tiers in the constructor. That didn't make the cut for the time I alotted for the project.

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
docker compose exec basket-php vendor/bin/phpstan analyze -l 9 src
docker compose exec basket-php vendor/bin/phpstan analyze -l 6 tests
```
