prepare-test:
	@rm -rf var/cache/test/*

group-test: prepare-test
	@./phpunit.phar --group=$(group)

test: prepare-test
	@./phpunit.phar

cs-fixer:
	php php-cs-fixer.phar fix src
	php php-cs-fixer.phar fix tests