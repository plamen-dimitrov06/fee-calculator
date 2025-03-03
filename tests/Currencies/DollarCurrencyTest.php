<?php

declare(strict_types=1);

namespace FeeCalculator\Tests\Currencies;

use FeeCalculator\Currencies\DollarCurrency;
use PHPUnit\Framework\TestCase;

class DollarCurrencyTest extends TestCase
{
    public function testConversionFromEuroRoundsCorrectly()
    {
        $sut = new DollarCurrency();
        $this->assertEquals(1.15, $sut->convertFromEuro(1));
    }
}
