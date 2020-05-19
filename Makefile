install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 bin/ tests/ src/

test:
	composer run-script phpunit tests

test-ci:
	composer run-script phpunit tests -- --coverage-clover ./build/logs/clover.xml