<?php

namespace App\Service;

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
