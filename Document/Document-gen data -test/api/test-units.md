vendor/bin/codecept generate:test unit models/Post
vendor/bin/codecept run unit

##---------------Step by step------------------
  $ cd api 
  $ vendor/bin/codecept build
  $ ../vendor/bin/codecept generate:test unit models/Post
    Test was created in C:\xampp\htdocs\weshop-v4.0-api\api\tests\unit\PostTest.php
    
  $ ../vendor/bin/codecept run unit
  
  Api\tests.unit Tests (7) -----------------------------------------------------------------------------------------------
  + PostTest: Validate empty (0.20s)
  + PostTest: Validate correct (0.02s)
  + PostTest: Save (0.03s)
  + PostTest: Publish (0.02s)
  + PostTest: Already published (0.02s)
  + PostTest: Draft (0.02s)
  + PostTest: Already drafted (0.03s)
  ------------------------------------------------------------------------------------------------------------------------
  2x DEPRECATION: PHPUnit\Framework\TestCase::setExpectedException deprecated in favor of expectException, expectExceptionMessage, and expectExceptionCode
  
  
  Time: 1.31 seconds, Memory: 12.00 MB
  
 $ ../vendor/bin/codecept run tests/unit/models/
