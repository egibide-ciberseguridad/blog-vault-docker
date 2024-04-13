#!make

ifneq (,$(wildcard ./.env))
    include .env
    export
else
$(error No se encuentra el fichero .env)
endif

help:
	@echo Opciones:
	@echo ---------------------------------------
	@echo start / stop / restart
	@echo build
	@echo logs
	@echo workspace
	@echo db
	@echo stats
	@echo clean
	@echo ---------------------------------------

_urls:
	${info }
	@echo ---------------------------------------
	@echo [Blog] https://${HTTPS_PORTAL_HOSTNAME}
	@echo ---------------------------------------

_start-command:
	@docker compose up -d --remove-orphans

start: _start-command _urls

stop:
	@docker compose stop

restart: stop start

build:
	@docker compose build

logs:
	@docker compose logs app

workspace:
	@docker compose exec app /bin/bash

db:
	@docker compose exec app php artisan migrate:fresh

stats:
	@docker stats

clean:
	@docker compose down -v --remove-orphans
