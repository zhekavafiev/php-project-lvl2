install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 src/ tests/ bin/

test:
	composer run-script phpunit tests/testdiff.php