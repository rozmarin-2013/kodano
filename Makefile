DC=docker-compose
DCEXEC=docker exec
API=kodano_php
COMPOSER=${DCEXEC} ${API} composer
CONSOLE=${DCEXEC} ${API} php bin/console

init:
	${DC} up -d --build
	${COMPOSER} install
	${CONSOLE} doctrine:migrations:migrate -n
	${CONSOLE} doctrine:fixtures:load --no-interaction

###> DOCKER COMPOSE
up:
	${DC} up -d --build

build:
	${DC} build

down:
	${DC} down

stop:
	${DC} stop
###< DOCKER COMPOSE

### Composer
composer-install:
	${COMPOSER} install --no-progress


### develop migrations ###
refresh:
	${CONSOLE} doctrine:migrations:migrate first -n
	${CONSOLE} doctrine:migrations:migrate -n

refresh-fixtures:
	${CONSOLE} doctrine:migrations:migrate first -n
	${CONSOLE} doctrine:migrations:migrate -n
	${CONSOLE} doctrine:fixtures:load --no-interaction

reload:
	${CONSOLE} doctrine:database:drop --force
	${CONSOLE} doctrine:database:create
	${CONSOLE} doctrine:migrations:migrate --no-interaction
### develop migrations ###

### run tests ###
test:
	${DCEXEC} ${API} php bin/phpunit
### run tests ###
