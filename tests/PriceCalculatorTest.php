<?php

use PHPUnit\Framework\TestCase;
use App\Service\PriceCalculator;

/**
 * Unit tests for PriceCalculator.
 *
 * These tests assert the basic arithmetic and rounding behaviour used by
 * the application when computing totals with and without VAT.
 */
class PriceCalculatorTest extends TestCase
{
    private PriceCalculator $calculator;

    protected function setUp(): void
    {
        // Create a fresh instance for each test
        $this->calculator = new PriceCalculator();
    }

    public function testCalculateWithoutVat()
    {
        $result = $this->calculator->calculateWithoutVat(100, 3);
        $this->assertEquals(300, $result, "Cena bez DPH by měla být 300");
    }

    public function testCalculateWithVat()
    {
        $totalWithoutVat = 300;
        $vatRate = 21;
        $result = $this->calculator->calculateWithVat($totalWithoutVat, $vatRate);
        $this->assertEquals(363, $result, "Cena s DPH by měla být 363");
    }
}
