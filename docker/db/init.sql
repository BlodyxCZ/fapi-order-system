CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price_without_vat DECIMAL(10,2) NOT NULL,
  vat_rate DECIMAL(5,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(255) NOT NULL,
  last_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(50) NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  total_without_vat DECIMAL(10,2) NOT NULL,
  total_with_vat DECIMAL(10,2) NOT NULL,
  currency_rate DECIMAL(10,4),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, price_without_vat, vat_rate)
VALUES
('Online kurz AI', 1990.00, 21.00),
('E-book Programování', 490.00, 21.00);