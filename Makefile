init:
	make down && make rm_volume && make build
build:
	docker-compose build --no-cache
up:
	docker compose up -d
down:
	docker compose down
root_old_mysql:
	docker compose exec old_mysql bash -c 'mysql -u root -psecret'
old_mysql:
	docker compose exec old_mysql bash -c 'mysql -u phper -psecret old_mysql'
new_mysql:
	docker compose exec new_mysql bash -c 'mysql -u phper -psecret new_mysql'
new_app:
	docker compose exec new_app bash
rm_volume:
	docker volume rm laravel-debezium_new_mysql_data laravel-debezium_old_mysql_data
