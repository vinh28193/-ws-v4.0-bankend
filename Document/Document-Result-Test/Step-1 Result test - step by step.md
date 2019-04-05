$ vendor/bin/codecept build
  
$ vendor/bin/codecept run
Codeception PHP Testing Framework v2.6.0
Powered by PHPUnit 8.1-g36f92d5 by Sebastian Bergmann and contributors.
Running with seed:



[common\tests]: tests from C:\xampp\htdocs\weshop-v4.0-api\common


Common\tests.unit Tests (3) --------------------------------------------------------------------------------------------
+ AdditionalFeeTest: Get store manager (0.01s)
E AdditionalFeeTest: Set additional fees (0.03s)
E OrderTest: Rules | test with rule required (0.06s)
------------------------------------------------------------------------------------------------------------------------


[api\tests]: tests from C:\xampp\htdocs\weshop-v4.0-api\api


Api\tests.api Tests (2) ------------------------------------------------------------------------------------------------
+ CreateOrderCest: Try to test (0.00s)
E CreateOrderCest: Create order via api (2.27s)
------------------------------------------------------------------------------------------------------------------------

Api\tests.functional Tests (0) -----------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

Api\tests.unit Tests (0) -----------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------


[backend\tests]: tests from C:\xampp\htdocs\weshop-v4.0-api\backend


Backend\tests.acceptance Tests (5) -------------------------------------------------------------------------------------
E LoginTestCest: Ensure that login works
E PostsTestCest: Ensure that post index page works
E PostsTestCest: Ensure that post view page works
E PostsTestCest: Ensure that post create page works
E PostsTestCest: Test delete
------------------------------------------------------------------------------------------------------------------------

Backend\tests.api Tests (6) --------------------------------------------------------------------------------------------
E PostsApiCest: Test get all (0.00s)
E PostsApiCest: Test get one (0.00s)
E PostsApiCest: Test get not found (0.00s)
E PostsApiCest: Test create (0.00s)
E PostsApiCest: Test update (0.00s)
E PostsApiCest: Test delete (0.00s)
------------------------------------------------------------------------------------------------------------------------

Backend\tests.functional Tests (12) ------------------------------------------------------------------------------------
E LoginCest: Check empty (0.03s)
E LoginCest: Check wrong password (0.00s)
E LoginCest: Check valid login (0.00s)
E PostsFuntionCest: Test index (0.01s)
E PostsFuntionCest: Test view (0.01s)
E PostsFuntionCest: Test create invalid (0.01s)
E PostsFuntionCest: Test create valid (0.01s)
E PostsFuntionCest: Test update (0.01s)
E PostsFuntionCest: Test delete (0.01s)
E SignupCest: Signup with empty fields (0.00s)
E SignupCest: Signup with wrong email (0.00s)
E SignupCest: Signup successfully (0.00s)
------------------------------------------------------------------------------------------------------------------------

Backend\tests.unit Tests (0) -------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------


[userbackend\tests]: tests from C:\xampp\htdocs\weshop-v4.0-api\userbackend


Userbackend\tests.acceptance Tests (9) ---------------------------------------------------------------------------------
E AboutCest: Ensure that about works
E ContactCest: Ensure that contact page works
E ContactCest: Contact form can be submitted
E HomeCest: Check home
E LoginCest: Ensure that login works
E PostsCest: Ensure that post index page works
E PostsCest: Ensure that post view page works
E PostsCest: Ensure that post create page works
E PostsCest: Test delete
------------------------------------------------------------------------------------------------------------------------

Userbackend\tests.api Tests (6) ----------------------------------------------------------------------------------------
E PostsCest: Test get all (0.00s)
E PostsCest: Test get one (0.00s)
E PostsCest: Test get not found (0.00s)
E PostsCest: Test create (0.00s)
E PostsCest: Test update (0.00s)
E PostsCest: Test delete (0.00s)
------------------------------------------------------------------------------------------------------------------------

Userbackend\tests.functional Tests (18) --------------------------------------------------------------------------------
+ AboutCest: Check about (0.30s)
+ ContactCest: Check contact (0.07s)
+ ContactCest: Check contact submit no data (0.10s)
+ ContactCest: Check contact submit not correct email (0.06s)
+ ContactCest: Check contact submit correct data (0.18s)
x HomeCest: Check open (0.02s)
+ LoginCest: Check empty (0.02s)
x LoginCest: Check wrong password (0.01s)
x LoginCest: Check valid login (0.01s)
E PostsCest: Test index (0.02s)
E PostsCest: Test view (0.02s)
E PostsCest: Test create invalid (0.01s)
E PostsCest: Test create valid (0.01s)
E PostsCest: Test update (0.00s)
E PostsCest: Test delete (0.00s)
+ SignupCest: Signup with empty fields (0.05s)
E SignupCest: Signup with wrong email (0.05s)
E SignupCest: Signup successfully (0.03s)
------------------------------------------------------------------------------------------------------------------------

Userbackend\tests.unit Tests (0) ---------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------
1) AdditionalFeeTest: Set additional fees
 Test  ..\common\tests\unit\components\AdditionalFeeTest.php:testSetAdditionalFees

  [yii\base\UnknownMethodException] Calling unknown method: common\tests\_support\AdditionalFeeObject::setAdditionalFees()

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Component.php:300
#2  C:\xampp\htdocs\weshop-v4.0-api\common\tests\unit\components\AdditionalFeeTest.php:39
2) OrderTest: Rules | test with rule required
 Test  ..\common\tests\unit\models\OrderTest.php:testRules | test with rule required

  [yii\base\UnknownPropertyException] Getting unknown property: common\models\Order::created_at

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Component.php:154
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\BaseActiveRecord.php:298
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\validators\Validator.php:254
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Model.php:367
#5  C:\xampp\htdocs\weshop-v4.0-api\common\tests\unit\models\OrderTest.php:29
#6  common\tests\models\OrderTest->common\tests\models\{closure}
#7  C:\xampp\htdocs\weshop-v4.0-api\common\tests\unit\models\OrderTest.php:61
3) CreateOrderCest: Create order via api
 Test  ..\api\tests\api\CreateOrderCest.php:createOrderViaAPI

  [GuzzleHttp\Exception\ConnectException] cURL error 7: Failed to connect to 127.0.0.1 port 8880: Connection refused (see https://curl.haxx.se/libcurl/c/libcurl-errors.html)


Scenario Steps:

 3. $I->sendPOST("/1/order/create","{\r\n            "store_id" : "Store ID",\r\n            "type_order" : "Type O...") at ..\api\tests\api\CreateOrderCest.php:19
 2. $I->haveHttpHeader("X-Access-Token","8ac9d03d9a2f6b467925f209e791254e") at ..\api\tests\api\CreateOrderCest.php:18
 1. $I->haveHttpHeader("Content-Type","application/json") at ..\api\tests\api\CreateOrderCest.php:17

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\guzzlehttp\guzzle\src\Handler\CurlFactory.php:185
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\guzzlehttp\guzzle\src\Handler\CurlFactory.php:149
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\guzzlehttp\guzzle\src\Handler\CurlFactory.php:102
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\guzzlehttp\guzzle\src\Handler\CurlHandler.php:43
#5  C:\xampp\htdocs\weshop-v4.0-api\vendor\guzzlehttp\guzzle\src\PrepareBodyMiddleware.php:66
#6  C:\xampp\htdocs\weshop-v4.0-api\vendor\guzzlehttp\guzzle\src\Middleware.php:36
#7  C:\xampp\htdocs\weshop-v4.0-api\vendor\guzzlehttp\guzzle\src\RedirectMiddleware.php:54
#8  C:\xampp\htdocs\weshop-v4.0-api\vendor\guzzlehttp\guzzle\src\Middleware.php:60
#9  C:\xampp\htdocs\weshop-v4.0-api\vendor\guzzlehttp\guzzle\src\HandlerStack.php:67
#10 C:\xampp\htdocs\weshop-v4.0-api\vendor\guzzlehttp\guzzle\src\Client.php:277
4) LoginTestCest: Ensure that login works
 Test  ..\backend\tests\acceptance\LoginCest.php:ensureThatLoginWorks

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
5) PostsTestCest: Ensure that post index page works
 Test  ..\backend\tests\acceptance\PostsCest.php:testIndex

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
6) PostsTestCest: Ensure that post view page works
 Test  ..\backend\tests\acceptance\PostsCest.php:testView

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
7) PostsTestCest: Ensure that post create page works
 Test  ..\backend\tests\acceptance\PostsCest.php:testCreate

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
8) PostsTestCest: Test delete
 Test  ..\backend\tests\acceptance\PostsCest.php:testDelete

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
9) PostsApiCest: Test get all
 Test  ..\backend\tests\api\PostsCest.php:testGetAll

  [InjectionException] Failed to inject dependencies in instance of 'tests\api\PostsApiCest'. Class ApiTester does not exist

10) PostsApiCest: Test get one
 Test  ..\backend\tests\api\PostsCest.php:testGetOne

  [InjectionException] Failed to inject dependencies in instance of 'tests\api\PostsApiCest'. Class ApiTester does not exist

11) PostsApiCest: Test get not found
 Test  ..\backend\tests\api\PostsCest.php:testGetNotFound

  [InjectionException] Failed to inject dependencies in instance of 'tests\api\PostsApiCest'. Class ApiTester does not exist

12) PostsApiCest: Test create
 Test  ..\backend\tests\api\PostsCest.php:testCreate

  [InjectionException] Failed to inject dependencies in instance of 'tests\api\PostsApiCest'. Class ApiTester does not exist

13) PostsApiCest: Test update
 Test  ..\backend\tests\api\PostsCest.php:testUpdate

  [InjectionException] Failed to inject dependencies in instance of 'tests\api\PostsApiCest'. Class ApiTester does not exist

14) PostsApiCest: Test delete
 Test  ..\backend\tests\api\PostsCest.php:testDelete

  [InjectionException] Failed to inject dependencies in instance of 'tests\api\PostsApiCest'. Class ApiTester does not exist

15) LoginCest: Check empty
 Test  ..\backend\tests\functional\LoginCest.php:checkEmpty

  [yii\base\InvalidConfigException] The table does not exist: visitor


Scenario Steps:

 1. $I->amOnRoute("site/login") at ..\backend\tests\functional\LoginCest.php:29

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:393
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:414
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:178
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\BaseActiveRecord.php:112
#5  C:\xampp\htdocs\weshop-v4.0-api\vendor\johnsnook\yii2-ip-filter\Module.php:164
#6  johnsnook\ipFilter\Module->metalDetector
#7  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Component.php:627
#8  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Module.php:700
#9  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Controller.php:145
#10 C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Module.php:528
16) LoginCest: Check wrong password
 Test  ..\backend\tests\functional\LoginCest.php:checkWrongPassword

  [yii\base\InvalidConfigException] The table does not exist: visitor


Scenario Steps:

 1. $I->amOnRoute("site/login") at ..\backend\tests\functional\LoginCest.php:29

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:393
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:414
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:178
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\BaseActiveRecord.php:112
#5  C:\xampp\htdocs\weshop-v4.0-api\vendor\johnsnook\yii2-ip-filter\Module.php:164
#6  johnsnook\ipFilter\Module->metalDetector
#7  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Component.php:627
#8  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Module.php:700
#9  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Controller.php:145
#10 C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Module.php:528
17) LoginCest: Check valid login
 Test  ..\backend\tests\functional\LoginCest.php:checkValidLogin

  [yii\base\InvalidConfigException] The table does not exist: visitor


Scenario Steps:

 1. $I->amOnRoute("site/login") at ..\backend\tests\functional\LoginCest.php:29

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:393
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:414
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:178
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\BaseActiveRecord.php:112
#5  C:\xampp\htdocs\weshop-v4.0-api\vendor\johnsnook\yii2-ip-filter\Module.php:164
#6  johnsnook\ipFilter\Module->metalDetector
#7  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Component.php:627
#8  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Module.php:700
#9  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Controller.php:145
#10 C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Module.php:528
18) PostsFuntionCest: Test index
 Test  ..\backend\tests\functional\PostsCest.php:testIndex

  [yii\base\InvalidConfigException] Table does not exist: {{%post}}


Scenario Steps:

 1. $I->haveFixtures({"post":{"class":"tests\\fixtures\\PostFixture","dataFile":"C:\\xampp\\htdocs\\weshop-v4.0-api...}) at ..\backend\tests\functional\PostsCest.php:17

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:152
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:125
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:115
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\FixtureTrait.php:121
#5  Codeception\Module\Yii2->haveFixtures
#6  C:\xampp\htdocs\weshop-v4.0-api\backend\tests\_support\_generated\FunctionalTesterActions.php:481
#7  C:\xampp\htdocs\weshop-v4.0-api\backend\tests\functional\PostsCest.php:17
#8  tests\functional\PostsFuntionCest->_before
19) PostsFuntionCest: Test view
 Test  ..\backend\tests\functional\PostsCest.php:testView

  [yii\base\InvalidConfigException] Table does not exist: {{%post}}


Scenario Steps:

 1. $I->haveFixtures({"post":{"class":"tests\\fixtures\\PostFixture","dataFile":"C:\\xampp\\htdocs\\weshop-v4.0-api...}) at ..\backend\tests\functional\PostsCest.php:17

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:152
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:125
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:115
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\FixtureTrait.php:121
#5  Codeception\Module\Yii2->haveFixtures
#6  C:\xampp\htdocs\weshop-v4.0-api\backend\tests\_support\_generated\FunctionalTesterActions.php:481
#7  C:\xampp\htdocs\weshop-v4.0-api\backend\tests\functional\PostsCest.php:17
#8  tests\functional\PostsFuntionCest->_before
20) PostsFuntionCest: Test create invalid
 Test  ..\backend\tests\functional\PostsCest.php:testCreateInvalid

  [yii\base\InvalidConfigException] Table does not exist: {{%post}}


Scenario Steps:

 1. $I->haveFixtures({"post":{"class":"tests\\fixtures\\PostFixture","dataFile":"C:\\xampp\\htdocs\\weshop-v4.0-api...}) at ..\backend\tests\functional\PostsCest.php:17

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:152
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:125
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:115
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\FixtureTrait.php:121
#5  Codeception\Module\Yii2->haveFixtures
#6  C:\xampp\htdocs\weshop-v4.0-api\backend\tests\_support\_generated\FunctionalTesterActions.php:481
#7  C:\xampp\htdocs\weshop-v4.0-api\backend\tests\functional\PostsCest.php:17
#8  tests\functional\PostsFuntionCest->_before
21) PostsFuntionCest: Test create valid
 Test  ..\backend\tests\functional\PostsCest.php:testCreateValid

  [yii\base\InvalidConfigException] Table does not exist: {{%post}}


Scenario Steps:

 1. $I->haveFixtures({"post":{"class":"tests\\fixtures\\PostFixture","dataFile":"C:\\xampp\\htdocs\\weshop-v4.0-api...}) at ..\backend\tests\functional\PostsCest.php:17

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:152
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:125
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:115
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\FixtureTrait.php:121
#5  Codeception\Module\Yii2->haveFixtures
#6  C:\xampp\htdocs\weshop-v4.0-api\backend\tests\_support\_generated\FunctionalTesterActions.php:481
#7  C:\xampp\htdocs\weshop-v4.0-api\backend\tests\functional\PostsCest.php:17
#8  tests\functional\PostsFuntionCest->_before
22) PostsFuntionCest: Test update
 Test  ..\backend\tests\functional\PostsCest.php:testUpdate

  [yii\base\InvalidConfigException] Table does not exist: {{%post}}


Scenario Steps:

 1. $I->haveFixtures({"post":{"class":"tests\\fixtures\\PostFixture","dataFile":"C:\\xampp\\htdocs\\weshop-v4.0-api...}) at ..\backend\tests\functional\PostsCest.php:17

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:152
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:125
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:115
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\FixtureTrait.php:121
#5  Codeception\Module\Yii2->haveFixtures
#6  C:\xampp\htdocs\weshop-v4.0-api\backend\tests\_support\_generated\FunctionalTesterActions.php:481
#7  C:\xampp\htdocs\weshop-v4.0-api\backend\tests\functional\PostsCest.php:17
#8  tests\functional\PostsFuntionCest->_before
23) PostsFuntionCest: Test delete
 Test  ..\backend\tests\functional\PostsCest.php:testDelete

  [yii\base\InvalidConfigException] Table does not exist: {{%post}}


Scenario Steps:

 1. $I->haveFixtures({"post":{"class":"tests\\fixtures\\PostFixture","dataFile":"C:\\xampp\\htdocs\\weshop-v4.0-api...}) at ..\backend\tests\functional\PostsCest.php:17

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:152
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:125
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:115
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\FixtureTrait.php:121
#5  Codeception\Module\Yii2->haveFixtures
#6  C:\xampp\htdocs\weshop-v4.0-api\backend\tests\_support\_generated\FunctionalTesterActions.php:481
#7  C:\xampp\htdocs\weshop-v4.0-api\backend\tests\functional\PostsCest.php:17
#8  tests\functional\PostsFuntionCest->_before
24) SignupCest: Signup with empty fields
 Test  ..\backend\tests\functional\SignupCest.php:signupWithEmptyFields

  [yii\base\InvalidConfigException] The table does not exist: visitor


Scenario Steps:

 1. $I->amOnRoute("site/signup") at ..\backend\tests\functional\SignupCest.php:14

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:393
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:414
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:178
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\BaseActiveRecord.php:112
#5  C:\xampp\htdocs\weshop-v4.0-api\vendor\johnsnook\yii2-ip-filter\Module.php:164
#6  johnsnook\ipFilter\Module->metalDetector
#7  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Component.php:627
#8  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Module.php:700
#9  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Controller.php:145
#10 C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Module.php:528
25) SignupCest: Signup with wrong email
 Test  ..\backend\tests\functional\SignupCest.php:signupWithWrongEmail

  [yii\base\InvalidConfigException] The table does not exist: visitor


Scenario Steps:

 1. $I->amOnRoute("site/signup") at ..\backend\tests\functional\SignupCest.php:14

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:393
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:414
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:178
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\BaseActiveRecord.php:112
#5  C:\xampp\htdocs\weshop-v4.0-api\vendor\johnsnook\yii2-ip-filter\Module.php:164
#6  johnsnook\ipFilter\Module->metalDetector
#7  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Component.php:627
#8  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Module.php:700
#9  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Controller.php:145
#10 C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Module.php:528
26) SignupCest: Signup successfully
 Test  ..\backend\tests\functional\SignupCest.php:signupSuccessfully

  [yii\base\InvalidConfigException] The table does not exist: visitor


Scenario Steps:

 1. $I->amOnRoute("site/signup") at ..\backend\tests\functional\SignupCest.php:14

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:393
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:414
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\ActiveRecord.php:178
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\BaseActiveRecord.php:112
#5  C:\xampp\htdocs\weshop-v4.0-api\vendor\johnsnook\yii2-ip-filter\Module.php:164
#6  johnsnook\ipFilter\Module->metalDetector
#7  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Component.php:627
#8  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Module.php:700
#9  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Controller.php:145
#10 C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\base\Module.php:528
27) AboutCest: Ensure that about works
 Test  tests\acceptance\AboutCest.php:ensureThatAboutWorks

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
28) ContactCest: Ensure that contact page works
 Test  tests\acceptance\ContactCest.php:contactPageWorks

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
29) ContactCest: Contact form can be submitted
 Test  tests\acceptance\ContactCest.php:contactFormCanBeSubmitted

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
30) HomeCest: Check home
 Test  tests\acceptance\HomeCest.php:checkHome

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
31) LoginCest: Ensure that login works
 Test  tests\acceptance\LoginCest.php:ensureThatLoginWorks

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
32) PostsCest: Ensure that post index page works
 Test  tests\acceptance\PostsCest.php:testIndex

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
33) PostsCest: Ensure that post view page works
 Test  tests\acceptance\PostsCest.php:testView

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
34) PostsCest: Ensure that post create page works
 Test  tests\acceptance\PostsCest.php:testCreate

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
35) PostsCest: Test delete
 Test  tests\acceptance\PostsCest.php:testDelete

  [ConnectionException] Can't connect to Webdriver at http://127.0.0.1:4444/wd/hub. Please make sure that Selenium Server or PhantomJS is running.

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:221
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\symfony\event-dispatcher\EventDispatcher.php:58
36) PostsCest: Test get all
 Test  tests\api\PostsCest.php:testGetAll

  [InjectionException] Failed to inject dependencies in instance of 'tests\api\PostsCest'. Class ApiTester does not exist

37) PostsCest: Test get one
 Test  tests\api\PostsCest.php:testGetOne

  [InjectionException] Failed to inject dependencies in instance of 'tests\api\PostsCest'. Class ApiTester does not exist

38) PostsCest: Test get not found
 Test  tests\api\PostsCest.php:testGetNotFound

  [InjectionException] Failed to inject dependencies in instance of 'tests\api\PostsCest'. Class ApiTester does not exist

39) PostsCest: Test create
 Test  tests\api\PostsCest.php:testCreate

  [InjectionException] Failed to inject dependencies in instance of 'tests\api\PostsCest'. Class ApiTester does not exist

40) PostsCest: Test update
 Test  tests\api\PostsCest.php:testUpdate

  [InjectionException] Failed to inject dependencies in instance of 'tests\api\PostsCest'. Class ApiTester does not exist

41) PostsCest: Test delete
 Test  tests\api\PostsCest.php:testDelete

  [InjectionException] Failed to inject dependencies in instance of 'tests\api\PostsCest'. Class ApiTester does not exist

42) PostsCest: Test index
 Test  tests\functional\PostsCest.php:testIndex

  [yii\base\InvalidConfigException] Table does not exist: {{%post}}


Scenario Steps:

 1. $I->haveFixtures({"post":{"class":"tests\\fixtures\\PostFixture","dataFile":"C:\\xampp\\htdocs\\weshop-v4.0-api...}) at tests\functional\PostsCest.php:17

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:152
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:125
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:115
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\FixtureTrait.php:121
#5  Codeception\Module\Yii2->haveFixtures
#6  C:\xampp\htdocs\weshop-v4.0-api\userbackend\tests\_support\_generated\FunctionalTesterActions.php:481
#7  C:\xampp\htdocs\weshop-v4.0-api\userbackend\tests\functional\PostsCest.php:17
#8  tests\functional\PostsCest->_before
43) PostsCest: Test view
 Test  tests\functional\PostsCest.php:testView

  [yii\base\InvalidConfigException] Table does not exist: {{%post}}


Scenario Steps:

 1. $I->haveFixtures({"post":{"class":"tests\\fixtures\\PostFixture","dataFile":"C:\\xampp\\htdocs\\weshop-v4.0-api...}) at tests\functional\PostsCest.php:17

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:152
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:125
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:115
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\FixtureTrait.php:121
#5  Codeception\Module\Yii2->haveFixtures
#6  C:\xampp\htdocs\weshop-v4.0-api\userbackend\tests\_support\_generated\FunctionalTesterActions.php:481
#7  C:\xampp\htdocs\weshop-v4.0-api\userbackend\tests\functional\PostsCest.php:17
#8  tests\functional\PostsCest->_before
44) PostsCest: Test create invalid
 Test  tests\functional\PostsCest.php:testCreateInvalid

  [yii\base\InvalidConfigException] Table does not exist: {{%post}}


Scenario Steps:

 1. $I->haveFixtures({"post":{"class":"tests\\fixtures\\PostFixture","dataFile":"C:\\xampp\\htdocs\\weshop-v4.0-api...}) at tests\functional\PostsCest.php:17

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:152
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:125
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:115
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\FixtureTrait.php:121
#5  Codeception\Module\Yii2->haveFixtures
#6  C:\xampp\htdocs\weshop-v4.0-api\userbackend\tests\_support\_generated\FunctionalTesterActions.php:481
#7  C:\xampp\htdocs\weshop-v4.0-api\userbackend\tests\functional\PostsCest.php:17
#8  tests\functional\PostsCest->_before
45) PostsCest: Test create valid
 Test  tests\functional\PostsCest.php:testCreateValid

  [yii\base\InvalidConfigException] Table does not exist: {{%post}}


Scenario Steps:

 1. $I->haveFixtures({"post":{"class":"tests\\fixtures\\PostFixture","dataFile":"C:\\xampp\\htdocs\\weshop-v4.0-api...}) at tests\functional\PostsCest.php:17

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:152
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:125
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:115
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\FixtureTrait.php:121
#5  Codeception\Module\Yii2->haveFixtures
#6  C:\xampp\htdocs\weshop-v4.0-api\userbackend\tests\_support\_generated\FunctionalTesterActions.php:481
#7  C:\xampp\htdocs\weshop-v4.0-api\userbackend\tests\functional\PostsCest.php:17
#8  tests\functional\PostsCest->_before
46) PostsCest: Test update
 Test  tests\functional\PostsCest.php:testUpdate

  [yii\base\InvalidConfigException] Table does not exist: {{%post}}


Scenario Steps:

 1. $I->haveFixtures({"post":{"class":"tests\\fixtures\\PostFixture","dataFile":"C:\\xampp\\htdocs\\weshop-v4.0-api...}) at tests\functional\PostsCest.php:17

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:152
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:125
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:115
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\FixtureTrait.php:121
#5  Codeception\Module\Yii2->haveFixtures
#6  C:\xampp\htdocs\weshop-v4.0-api\userbackend\tests\_support\_generated\FunctionalTesterActions.php:481
#7  C:\xampp\htdocs\weshop-v4.0-api\userbackend\tests\functional\PostsCest.php:17
#8  tests\functional\PostsCest->_before
47) PostsCest: Test delete
 Test  tests\functional\PostsCest.php:testDelete

  [yii\base\InvalidConfigException] Table does not exist: {{%post}}


Scenario Steps:

 1. $I->haveFixtures({"post":{"class":"tests\\fixtures\\PostFixture","dataFile":"C:\\xampp\\htdocs\\weshop-v4.0-api...}) at tests\functional\PostsCest.php:17

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:152
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:125
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\ActiveFixture.php:115
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\test\FixtureTrait.php:121
#5  Codeception\Module\Yii2->haveFixtures
#6  C:\xampp\htdocs\weshop-v4.0-api\userbackend\tests\_support\_generated\FunctionalTesterActions.php:481
#7  C:\xampp\htdocs\weshop-v4.0-api\userbackend\tests\functional\PostsCest.php:17
#8  tests\functional\PostsCest->_before
48) SignupCest: Signup with wrong email
 Test  tests\functional\SignupCest.php:signupWithWrongEmail

  [yii\db\Exception] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'customer.username' in 'where clause'
The SQL being executed was: SELECT EXISTS(SELECT * FROM `customer` WHERE `customer`.`username`='tester')


Scenario Steps:

 2. $I->submitForm("#form-signup",{"SignupForm[username]":"tester","SignupForm[email]":"ttttt","SignupForm[password...}) at tests\functional\SignupCest.php:32
 1. $I->amOnRoute("site/signup") at tests\functional\SignupCest.php:14

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\Schema.php:664
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\Command.php:1295
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\Command.php:1158
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\Command.php:425
#5  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\Query.php:425
#6  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\validators\UniqueValidator.php:187
#7  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\validators\UniqueValidator.php:145
#8  yii\validators\UniqueValidator->yii\validators\{closure}
#9  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\Connection.php:1059
#10 C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\validators\UniqueValidator.php:146
49) SignupCest: Signup successfully
 Test  tests\functional\SignupCest.php:signupSuccessfully

  [yii\db\Exception] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'customer.username' in 'where clause'
The SQL being executed was: SELECT EXISTS(SELECT * FROM `customer` WHERE `customer`.`username`='tester')


Scenario Steps:

 2. $I->submitForm("#form-signup",{"SignupForm[username]":"tester","SignupForm[email]":"tester.email@example.com","...}) at tests\functional\SignupCest.php:45
 1. $I->amOnRoute("site/signup") at tests\functional\SignupCest.php:14

#1  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\Schema.php:664
#2  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\Command.php:1295
#3  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\Command.php:1158
#4  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\Command.php:425
#5  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\Query.php:425
#6  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\validators\UniqueValidator.php:187
#7  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\validators\UniqueValidator.php:145
#8  yii\validators\UniqueValidator->yii\validators\{closure}
#9  C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\db\Connection.php:1059
#10 C:\xampp\htdocs\weshop-v4.0-api\vendor\yiisoft\yii2\validators\UniqueValidator.php:146
1) HomeCest: Check open
 Test  tests\functional\HomeCest.php:checkOpen
Failed asserting that  on page /index-test.php/site/login
-->  Login Toggle navigation WESHOP @2019 RESTful APIAPI 1.0 Documenation Signup Login Home Login Login Please fill out the following fields to login: Username Password Remember Me If you forgot your password you can reset it. Login © WESHOP @2019 RESTful API 2019 Powered by Yii Framework
--> contains "My Company".

Scenario Steps:

 2. $I->see("My Company") at tests\functional\HomeCest.php:12
 1. $I->amOnPage("/index-test.php") at tests\functional\HomeCest.php:11

2) LoginCest: Check wrong password
 Test  tests\functional\LoginCest.php:checkWrongPassword
Failed asserting that any element by '.help-block' on page /index-test.php/site/login
+ <p class="help-block help-block-error">Username cannot be blank.</p>
+ <p class="help-block help-block-error">Password cannot be blank.</p>
+ <p class="help-block help-block-error"></p>
contains text 'Incorrect username or password.'

Scenario Steps:

 3. $I->see("Incorrect username or password.",".help-block") at tests\_support\FunctionalTester.php:26
 2. $I->submitForm("#login-form",{"LoginForm[username]":"admin","LoginForm[password]":"wrong"}) at tests\functional\LoginCest.php:49
 1. $I->amOnRoute("site/login") at tests\functional\LoginCest.php:29

3) LoginCest: Check valid login
 Test  tests\functional\LoginCest.php:checkValidLogin
Failed asserting that any element by 'form button[type=submit]' on page /index-test.php/site/login
+ <button class="btn btn-primary" name="login-button" type="submit">Login</button>
contains text 'Logout (erau)'

Scenario Steps:

 3. $I->see("Logout (erau)","form button[type=submit]") at tests\functional\LoginCest.php:56
 2. $I->submitForm("#login-form",{"LoginForm[username]":"erau","LoginForm[password]":"password_0"}) at tests\functional\LoginCest.php:55
 1. $I->amOnRoute("site/login") at tests\functional\LoginCest.php:29



Time: 26.25 seconds, Memory: 34.00 MB

There were 49 errors:

---------

--

There were 3 failures:

---------

---------

---------

ERRORS!
Tests: 61, Assertions: 26, Errors: 49, Failures: 3.

admin@DESKTOP-PL28AOR MINGW64 /c/xampp/htdocs/weshop-v4.0-api (master)
