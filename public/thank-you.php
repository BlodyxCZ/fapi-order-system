<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Service\CurrencyCalculator;

$pdo = Database::connect();

$orderId = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT o.*, p.name 
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.id = ?
");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    die("Objednávka nenalezena");
}

// Přepočet na EUR
$currencyService = new CurrencyCalculator();
$totalEur = $currencyService->convertToEur($order['total_with_vat']);
$rate = $currencyService->getRate();
?>

<h1>Děkujeme za objednávku</h1>

<p>Jméno: <?= htmlspecialchars($order['first_name']) ?> <?= htmlspecialchars($order['last_name']) ?></p>
<p>Email: <?= htmlspecialchars($order['email']) ?></p>
<p>Telefon: <?= htmlspecialchars($order['phone']) ?></p>
<p>Produkt: <?= htmlspecialchars($order['name']) ?></p>
<p>Množství: <?= $order['quantity'] ?></p>
<p>Cena bez DPH: <?= $order['total_without_vat'] ?> Kč</p>
<p>Cena s DPH: <?= $order['total_with_vat'] ?> Kč</p>
<p>Cena v EUR: <?= $totalEur ?> € (Kurz: <?= $rate ?>)</p>
