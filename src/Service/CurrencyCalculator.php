<?php

namespace App\Service;

/**
 * CurrencyCalculator
 *
 * Fetches the Czech National Bank daily exchange rates (plain text) and
 * provides a simple CZK -> EUR conversion using the fetched EUR rate.
 *
 * Implementation notes:
 * - The CNB daily file contains a small header, then pipe-separated rows.
 * - Each row has columns like: country|unit|currency|amount|rate
 *   (this class expects EUR rows and uses the provided rate and unit).
 */
class CurrencyCalculator
{
    private $eurRate;

    public function __construct()
    {
        $this->eurRate = $this->fetchEurRate();
    }

    /**
     * Download and parse CNB daily exchange file to find EUR rate.
     *
     * @return float rate (CZK per 1 EUR)
     * @throws \Exception on download or parse errors
     */
    private function fetchEurRate(): float
    {
        $url = 'https://www.cnb.cz/en/financial_markets/foreign_exchange_market/exchange_rate_fixing/daily.txt';
        $content = file_get_contents($url);

        if ($content === false) {
            throw new \Exception("Nepodařilo se stáhnout kurz ČNB");
        }

        // Remove the two-line header and iterate over rate lines
        $lines = explode("\n", $content);
        $lines = array_slice($lines, 2);

        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }
            $parts = explode('|', $line);
            // CNB format: country|unit|currency|amount|rate
            if (isset($parts[3]) && $parts[3] === 'EUR') {
                // Rate uses comma decimal separator; amount is the number of currency units
                $rawRate = str_replace(',', '.', $parts[4]);
                $unit = intval($parts[2]);
                return floatval($rawRate) / ($unit > 0 ? $unit : 1);
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
