  ###-------Run------
   php -S 127.0.0.1:8880 -t api/web
   vendor/bin/codecept run -- -c api
   
 #-------------- generated api.suite.yml -------------------  
   $ codecept generate:suite api
   Helper \api\tests\Helper\Api was created in /Applications/XAMPP/xamppfiles/htdocs/weshop-v4.0-api/api/tests/_support/Helper/Api.php
   Actor ApiTester was created in /Applications/XAMPP/xamppfiles/htdocs/weshop-v4.0-api/api/tests/_support/ApiTester.php
   Suite config api.suite.yml was created.
    
   Next steps:
   1. Edit api.suite.yml to enable modules for this suite
   2. Create first test with generate:cest testName ( or test|cept) command
   3. Run tests of this suite with codecept run api command
   Suite api generated

 # ---------Tao File Test API -------------
  $ cd api/ 
  $ codecept generate:cest api CreateOrder
  Test was created in /Applications/XAMPP/xamppfiles/htdocs/weshop-v4.0-api/api/tests/api/CreateOrderCest.php
  
  
  #------------Done Test------------
  
  $ vendor/bin/codecept run --steps --debug --report -- -c api
  Codeception PHP Testing Framework v2.6.0
  Powered by PHPUnit 8.1-g36f92d5 by Sebastian Bergmann and contributors.
  Running with seed:
  
  CreateOrderCest: Try to test...............................................Ok
  CreateOrderCest: Create order via api error store..........................Ok
  CreateOrderCest: Create order via api done.................................Ok
  PostsApiCest: Test get all.................................................Ok
  PostsApiCest: Test get one.................................................Ok
  PostsApiCest: Test get not found...........................................Ok
  PostsApiCest: Test create..................................................Ok
  PostsApiCest: Test update..................................................Ok
  PostsApiCest: Test delete..................................................FAIL
