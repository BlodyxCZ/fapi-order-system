<?php

namespace App\Service;

/**
 * PriceCalculator
 *
 * Small service responsible for price calculations. Keeping this logic
 * here centralizes rounding rules and makes the calculations easy to test.
 */
class PriceCalculator
{
    public function calculateWithoutVat(float $price, int $quantity): float
    {
        return round($price * $quantity, 2);
    }

    public function calculateWithVat(float $totalWithoutVat, float $vatRate): float
    {
        return round($totalWithoutVat * (1 + $vatRate / 100), 2);
    }
}
