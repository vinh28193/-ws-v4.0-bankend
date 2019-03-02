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
  