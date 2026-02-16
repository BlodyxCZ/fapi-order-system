# FAPI Order System

A PHP-based order management system with integrated price calculation, VAT handling, and currency conversion capabilities.

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
│   └── nginx.conf         # Nginx configuration
├── docker-compose.yml     # Multi-container setup definition
└── Makefile               # Convenient command shortcuts
```

## Quick Start

### Prerequisites

- Docker and Docker Compose installed
- Windows, macOS, or Linux system

### Installation & Setup

1. **Clone/Navigate to the repository**
   ```bash
   cd fapi-order-system
   ```

2. **Start the development environment**
   ```bash
   make dev
   ```
   This will build and start all containers (PHP app, Nginx, MariaDB).

3. **Access the application**
   - Web interface: `http://localhost:8080`
   - Nginx serves files from the `public/` directory

4. **Set up the database**
   ```bash
   make bash
   # Inside the container, create the database and tables
   ```

## Available Commands

Use the Makefile for convenient command execution:

```bash
# Start development server with auto-build
make dev

# Run unit tests
make test

# Manage containers (stop and rebuild)
make restart

# Access container shell
make bash
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

Orders must meet the following validation criteria:
- **Email**: Valid email format (RFC standard)
- **Phone**: 9-15 digit number
- **Quantity**: Positive integer

## Testing

Run the test suite:

```bash
make test
```

Tests are located in `tests/` and use PHPUnit framework.

## Development

### Adding New Features

1. Create service classes in `src/Service/`
2. Add unit tests in `tests/`
3. Use dependency injection where appropriate
4. Follow PSR-4 autoloading conventions

### Database Queries

The application uses PDO with prepared statements for secure database operations. Examples can be found in `public/index.php`.

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

This project is created for educational and development purposes.

## Support & Contact

For issues or questions, refer to the project repository or contact the development team.
