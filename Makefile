install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 bin/ tests/ src/

test:
	composer run-script phpunit tests/