$ vendor/bin/codecept run --steps  --debug

###-------Run test Back end ------
   php -S 127.0.0.1:8980 -t backend/web
   vendor/bin/codecept run -- -c backend

###-------Run test Api ------
    php -S 127.0.0.1:8880 -t api/web
    vendor/bin/codecept run -- -c api  
    $ vendor/bin/codecept run --steps --debug -- -c api
   
###---------Selenium--------------------
  cd ~/Desktop
  $ java -jar selenium-server-standalone-3.141.59.jar   
  
  
$ vendor/bin/codecept run --report -- -c api
Codeception PHP Testing Framework v2.6.0
Powered by PHPUnit 8.1-g36f92d5 by Sebastian Bergmann and contributors.
Running with seed:

CreateOrderCest: Try to test...............................................Ok
CreateOrderCest: Create order via api......................................Ok
PostsApiCest: Test get all.................................................Ok
PostsApiCest: Test get one.................................................Ok
PostsApiCest: Test get not found...........................................FAIL
PostsApiCest: Test create..................................................FAIL
PostsApiCest: Test update..................................................FAIL
PostsApiCest: Test delete..................................................FAIL
