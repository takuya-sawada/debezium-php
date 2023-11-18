up:
	docker compose up -d
down:
	docker compose down
old_mysql:
	docker compose exec old_mysql bash -c 'mysql -u phper -psecret old_mysql'
new_mysql:
	docker compose exec new_mysql bash -c 'mysql -u phper -psecret new_mysql'
