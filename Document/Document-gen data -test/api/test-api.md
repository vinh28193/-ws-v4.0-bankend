
$ php -S 127.0.0.1:8880 -t api/web
 
$ vendor/bin/codecept run --steps --debug -- -c api
$ vendor/bin/codecept run --report -- -c api
$ yii fixture "User, Seller ,Customer , Product , Systemstateprovince , Systemdistrict , Address , Category , Categorycustompolicy , Order "

