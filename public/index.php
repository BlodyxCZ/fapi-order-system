<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Service\PriceCalculator;

/**
* Public order form and order processing endpoint.
*
* - Renders a simple HTML form to create an order.
* - On POST: validates input, calculates totals (with/without VAT)
*   and inserts the order into the database.
* - Uses App\Service\PriceCalculator to perform price math.
*/

$pdo = Database::connect();
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
$calculator = new PriceCalculator();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $productId = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Neplatný email");
    }

    if (!preg_match('/^[0-9]{9,15}$/', $phone)) {
        die("Neplatný telefon");
    }

    if ($quantity <= 0) {
        die("Neplatné množství");
    }

    // Load the product from the database to get price and VAT info
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if (!$product) {
        die("Produkt neexistuje");
    }

    $totalWithoutVat = $calculator->calculateWithoutVat(
        $product['price_without_vat'],
        $quantity
    );

    $totalWithVat = $calculator->calculateWithVat(
        $totalWithoutVat,
        $product['vat_rate']
    );

    $stmt = $pdo->prepare("
        INSERT INTO orders 
        (first_name, last_name, email, phone, product_id, quantity, total_without_vat, total_with_vat)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $firstName,
        $lastName,
        $email,
        $phone,
        $productId,
        $quantity,
        $totalWithoutVat,
        $totalWithVat
    ]);

    $orderId = $pdo->lastInsertId();

    header("Location: thank-you.php?id=" . $orderId);
    exit;
}


?>


<h1>Objednávka</h1>

<form method="POST">
    <input type="text" name="first_name" placeholder="Jméno" required><br>
    <input type="text" name="last_name" placeholder="Příjmení" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="tel" name="phone" id="phone" placeholder="Telefon" pattern="[0-9]{9,15}" required><br>
    <select name="product_id" id="product" required>
    <?php foreach ($products as $product): ?>
        <option 
            value="<?= $product['id'] ?>"
            data-price="<?= $product['price_without_vat'] ?>"
            data-vat="<?= $product['vat_rate'] ?>"
        >
            <?= htmlspecialchars($product['name']) ?>
        </option>
    <?php endforeach; ?>
    </select><br>
    <input type="number" name="quantity" id="quantity" min="1" value="1" required><br>

    <p>Cena bez DPH: <span id="priceWithoutVat">0 Kč</span></p>
    <p>Cena s DPH: <span id="priceWithVat">0 Kč</span></p>

    <button type="submit">Objednat</button>
</form>


<script>
    const productSelect = document.getElementById('product');
    const quantityInput = document.getElementById('quantity');

    const priceWithoutVatSpan = document.getElementById('priceWithoutVat');
    const priceWithVatSpan = document.getElementById('priceWithVat');

    function calculatePrice() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];

        const price = parseFloat(selectedOption.dataset.price);
        const vat = parseFloat(selectedOption.dataset.vat);
        const quantity = parseInt(quantityInput.value);

        if (isNaN(price) || isNaN(quantity) || quantity <= 0) {
            return;
        }

        const totalWithoutVat = (price * quantity).toFixed(2);
        const totalWithVat = (totalWithoutVat * (1 + vat / 100)).toFixed(2);

        priceWithoutVatSpan.textContent = totalWithoutVat;
        priceWithVatSpan.textContent = totalWithVat;
    }

    productSelect.addEventListener('change', calculatePrice);
    quantityInput.addEventListener('input', calculatePrice);

    calculatePrice();
</script>