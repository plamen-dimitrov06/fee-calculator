# fee-calculator
Calculate commission fees for banking operations (e.g. withdraw or deposit).

## TODO
* reader interface
* commission fee calculator, returns the fee
* fee calculator rules
    * deposit - 0.03% from amount
    * withdraw
        * private clients - 0.3% from amount
            * 1000 EUR for a week is FOC, limited to 3 withdraws per week
        * business clients - 0.5% from amount
* currency convertor to calculate the 1000 EUR rule

## Input

1. operation date in format Y-m-d
2. user's identificator, number
3. user's type, one of "private" or "business"
4. operation type, one of "deposit" or "withdraw"
5. operation amount (for example 2.12 or 3)
6. operation currency, one of EUR, USD, JPY