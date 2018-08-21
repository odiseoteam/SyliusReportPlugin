## Test the plugin

We are using Behat, PHPSpec and PHPUnit to test this plugin.

### How to run the tests

From the plugin root directory, run the following commands:

    ```bash
    $ (cd tests/Application && yarn install)
    $ (cd tests/Application && yarn run gulp)
    $ (cd tests/Application && bin/console assets:install web -e test)
    
    $ (cd tests/Application && bin/console doctrine:database:create -e test)
    $ (cd tests/Application && bin/console doctrine:schema:create -e test)
    ```

  - PHPUnit

    ```bash
    $ bin/phpunit
    ```

  - PHPSpec

    ```bash
    $ bin/phpspec run
    ```

  - Behat (non-JS scenarios)

    ```bash
    $ bin/behat --tags="~@javascript"
    ```

  - Behat (JS scenarios)
 
    1. Download [Chromedriver](https://sites.google.com/a/chromium.org/chromedriver/)
    
    2. Run Selenium server with previously downloaded Chromedriver:

    ```bash
    $ bin/selenium-server-standalone -Dwebdriver.chrome.driver=chromedriver
    ```
    
    3. Run test application's webserver on `localhost:8080`:
    
    ```bash
    $ (cd tests/Application && bin/console server:run 127.0.0.1:8080 -d web -e test)
    ```
    
    4. Run Behat:

    ```bash
    $ bin/behat --tags="@javascript"
    ```

### Opening Sylius with this plugin

  - Using `test` environment:

    ```bash
    $ (cd tests/Application && bin/console sylius:fixtures:load -e test)
    $ (cd tests/Application && bin/console server:run -d web -e test)
    ```
    
  - Using `dev` environment:

    ```bash
    $ (cd tests/Application && bin/console sylius:fixtures:load -e dev)
    $ (cd tests/Application && bin/console server:run -d web -e dev)