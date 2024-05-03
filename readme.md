# 
````shell
$ docker-compose up -d
$ docker exec -u www-data -it test_sf_web bash
$ composer install
$ php bin/console doctrine:database:drop -f
$ php bin/console doctrine:database:create
$ php bin/console d:m:m -n
$ php bin/console doctrine:fixtures:load -n
````