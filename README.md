# FAPI Order System

A small PHP order example demonstrating price/VAT calculations and CNB-based CZK→EUR conversion.

## Features

- **Order Management**: Process and manage customer orders through a web interface
- **Price Calculation**: Calculate product prices with and without VAT
- **Currency Conversion**: Real-time EUR/CZK conversion using Czech National Bank (CNB) exchange rates
- **Form Validation**: Server-side validation for customer data (email, phone number, order quantity)
- **Database Integration**: MariaDB database for storing products and orders
- **Docker Support**: Full containerized development environment

## Tech Stack

- **Backend**: PHP 8.2+
- **Web Server**: Nginx
- **Database**: MariaDB 10.6
- **Testing**: PHPUnit 11.5
- **Containerization**: Docker & Docker Compose
- **Architecture**: PSR-4 autoloading, OOP design patterns

## Project Structure

```
fapi-order-system/
├── public/                 # Web entry points
│   ├── index.php          # Main order form and processing
│   └── thank-you.php      # Order confirmation page
├── src/
│   ├── Database.php       # Database connection helper
│   └── Service/
│       ├── PriceCalculator.php    # Price and VAT calculations
│       └── CurrencyCalculator.php # EUR/CZK currency conversion
├── tests/
│   └── PriceCalculatorTest.php    # Unit tests for price calculator
├── docker/
│   ├── Dockerfile         # PHP application container
│   ├── nginx.conf         # Nginx configuration
│   └── db/
│       └── init.sql       # SQL to initialize database tables
├── composer.json          # PHP dependencies
├── composer.lock          # Locked dependencies
├── composer.phar          # Composer binary (optional)
├── docker-compose.yml     # Multi-container setup definition
└── Makefile               # Convenient command shortcuts
```

## Quick Start

### Prerequisites

- Docker & Docker Compose
- PHP & Composer (for running tests locally)

### Installation & Setup

1. Clone and install dependencies locally (optional):

```bash
git clone <repo-url>
cd fapi-order-system
```

1. a) Start the development environment with make command:

```bash
make dev
```

2. b) Start the development environment with Docker Compose:

```bash
docker compose up --build
```

3. Open `http://localhost:8080` to access the app (nginx serves `public/`).

## Available Commands

Use the `Makefile` for common tasks:

```bash
make dev      # build & start containers
make test     # run PHPUnit tests
make restart  # stop and rebuild containers
make bash     # open shell in app container
```

## API/Application Endpoints

- `GET /` - Display order form with available products
- `POST /` - Process order submission
- `GET /thank-you.php` - Order confirmation page

## Services

### PriceCalculator
Handles price calculations and VAT:
- `calculateWithoutVat(float $price, int $quantity)` - Calculate base price
- `calculateWithVat(float $totalWithoutVat, float $vatRate)` - Apply VAT

### CurrencyCalculator
Converts Czech Koruna to EUR using live CNB rates:
- `convertToEur(float $amountCzk)` - Convert CZK amount to EUR

### Database
Manages database connections:
- PDO-based MySQL/MariaDB connection with exception error mode

## Validation Rules

Server-side validation enforces:

- **Email**: RFC-compliant format
- **Phone**: 9–15 digits
- **Quantity**: positive integer

## Testing

Run unit tests with:

```bash
make test
```

Unit tests live in `tests/` and cover `PriceCalculator` behaviors.

## Development

To add features:

1. Add small, testable services to `src/Service/`.
2. Add unit tests under `tests/` and run `make test`.
3. Prefer constructor injection and PSR-4 class organization.

Database code uses PDO and prepared statements; see `public/index.php` for examples.

## Docker Configuration

The application uses three services:

| Service | Image | Purpose |
|---------|-------|---------|
| `app` | Custom PHP | Application server |
| `web` | nginx:stable-alpine | Web server & reverse proxy |
| `db` | mariadb:10.6 | Data persistence |

**Credentials** (Development Only):
- Database: `fapi`
- Root User: `root`
- Root Password: `root`

⚠️ **Security Note**: Never use these credentials in production. Use environment variables for sensitive configuration.

## License

Educational/example project — use and modify for learning.
