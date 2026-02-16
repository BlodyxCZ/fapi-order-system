dev:
	docker compose up --build

test:
	docker compose run --rm app vendor/bin/phpunit tests/PriceCalculatorTest.php

restart:
	docker compose down
	docker compose up --build -d

bash:
	docker compose run --rm app bash