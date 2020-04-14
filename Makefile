install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 bin/ tests/

test:
	composer run-script phpunit tests/