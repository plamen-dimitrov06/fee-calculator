<?php

declare(strict_types=1);

namespace FeeCalculator\Tests\Service;

use FeeCalculator\Models\EuroConverter;
use PHPUnit\Framework\TestCase;
use FeeCalculator\Rules\DepositCommission;
use FeeCalculator\Models\Transaction;

class EuroConverterTest extends TestCase
{
    public function testCaseInsensitiveCurrenciesConversion()
    {
        $sut = new EuroConverter();
        $this->assertEquals(130, $sut->convert('eur', 'jpy', 1));
    }

    public function testExceptionIsThrownWhenUsingUnknownCurrencies()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid conversion from EUR to BGN');
        $sut = new EuroConverter();
        $sut->convert('EUR', 'BGN', 10);
    }
}
