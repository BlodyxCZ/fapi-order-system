<?php

namespace App\Service;

class CurrencyCalculator
{
    private $eurRate;

    public function __construct()
    {
        $this->eurRate = $this->fetchEurRate();
    }

    private function fetchEurRate(): float
    {
        $url = 'https://www.cnb.cz/en/financial_markets/foreign_exchange_market/exchange_rate_fixing/daily.txt';
        $content = file_get_contents($url);

        if ($content === false) {
            throw new \Exception("Nepodařilo se stáhnout kurz ČNB");
        }

        $lines = explode("\n", $content);
        $lines = array_slice($lines, 2); // první 2 řádky jsou hlavička

        foreach ($lines as $line) {
            $parts = explode('|', $line);
            if ($parts[3] === 'EUR') {
                return floatval(str_replace(',', '.', $parts[4])) / intval($parts[2]); // kurs je v 2. sloupci (počítá se na 1 jednotku)
            }
        }

        throw new \Exception("Kurz EUR nenalezen");
    }

    public function convertToEur(float $amountCzk): float
    {
        return round($amountCzk / $this->eurRate, 2);
    }

    public function getRate(): float
    {
        return $this->eurRate;
    }
}
