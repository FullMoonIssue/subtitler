prepare-test:
	@rm -rf var/cache/test/*

group-test: prepare-test
	@./phpunit.phar --group=$(group)

test: prepare-test
	@./phpunit.phar