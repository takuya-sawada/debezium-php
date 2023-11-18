# laravel-debezium

## Docker

```shell
docker compose up -d

docker compose down
```

## MySQL

```shell
docker compose exec old_mysql bash -c 'mysql -u phper -psecret old_mysql'

docker compose exec new_mysql bash -c 'mysql -u phper -psecret new_mysql'
```