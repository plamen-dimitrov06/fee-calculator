<?php

declare(strict_types=1);

namespace FeeCalculator\Tests\Currencies;

use FeeCalculator\Currencies\YenCurrency;
use PHPUnit\Framework\TestCase;

class YenCurrencyTest extends TestCase
{
    public function testConversionFromEuroRoundsCorrectly()
    {
        $sut = new YenCurrency();
        $this->assertEquals(130, $sut->convertFromEuro(1));
    }
}
