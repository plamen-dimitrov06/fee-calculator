<?php

declare(strict_types=1);

namespace FeeCalculator\Tests\Models;

use FeeCalculator\Models\EuroConverter;
use PHPUnit\Framework\TestCase;
use FeeCalculator\Exceptions\ConversionException;

class EuroConverterTest extends TestCase
{
    public function testCaseInsensitiveCurrenciesConversion()
    {
        $sut = new EuroConverter();
        $this->assertEquals(130, $sut->convert('eur', 'jpy', 1));
    }

    public function testExceptionIsThrownWhenUsingUnknownCurrencies()
    {
        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage('Invalid conversion from EUR to BGN');
        $sut = new EuroConverter();
        $sut->convert('EUR', 'BGN', 10);
    }
}
