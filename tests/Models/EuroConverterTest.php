<?php

declare(strict_types=1);

namespace FeeCalculator\Tests\Models;

use FeeCalculator\Models\EuroConverter;
use PHPUnit\Framework\TestCase;

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
