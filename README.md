# fee-calculator
Calculate commission fees for banking operations (e.g. withdraw or deposit).

## Usage

1. composer install - This project uses Composer and you'll need to install the dependencies first.
2. php script.php tests/Service/fixtures/input.csv - Bootstrap autoloading and processes the test input in CLI.
3. composer phpunit - Run the tests.

## TODO
* write documentation
* ~~rename refactor currencies and transactions model or interface~~
* ~~extract method refactor the private withdraw rule (one of the methods is too long)~~
* final review

## Input

1. operation date in format Y-m-d
2. user's identificator, number
3. user's type, one of "private" or "business"
4. operation type, one of "deposit" or "withdraw"
5. operation amount (for example 2.12 or 3)
6. operation currency, one of EUR, USD, JPY
7. expected commission fee (used in tests)