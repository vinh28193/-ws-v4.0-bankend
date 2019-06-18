
INSTALLATION


```
Step1: Create a database named weshop-global
Step2:Clone the source code
git clone -b master https://gitlab.saobang.vn/weshop/weshop-v4.0-api.git

Step3: cd yii2-rest
Step4:composer install
Step5: ./init
Step6: vim common/config/main-local.php
change db information
 'db' => [

            'class' => 'yii\db\Connection',

            'dsn' => 'mysql:host=127.0.0.1;dbname=weshop-global',

            'username' => 'root',

            'password' => '123',

            'charset' => 'utf8',

        ],

Step7: Run db migration
           cd /var/www/weshop-v4.0-api
            ./yii migrate

Step8:
            point API end point URL to backend
             /var/www/weshop-v4.0-api/api/web


            point backend monitor all api  URL to backend
             /var/www/weshop-v4.0-api/backend/web

           point User backend    URL to userbackend
             /var/www/weshop-v4.0-api/userbackend/web
```

Try to run a frontend application by the following console command:

        php yii serve --docroot=@frontend/web --port=8080

Then run the backend in an other terminal window:

        php yii serve --docroot=@backend/web --port=8090

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
api
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```

###---------yii2 Test-------------
$ ./vendor/bin/phpunit backend/
PHPUnit 5.7.21-17-g4eba33748 by Sebastian Bergmann and contributors.



Time: 335 ms, Memory: 2.00MB

No tests executed!


### ----------Gii---------
http://hostname/index.php?r=gii
http://yii2-rest.back-end.local.vn/gii

###---------migrate-------------
 ./yii migrate/create order
Yii Migration Tool (based on Yii v2.0.13-dev)

Create new migration '/Applications/XAMPP/xamppfiles/htdocs/yii2-rest/console/migrations/m190214_073422_order.php'? (yes|no) [no]:yes
New migration created successfully.

###---------Fake data----------
https://subscription.packtpub.com/book/web_development/9781785281761/7/ch07lvl1sec78/pjax-jquery-plugin
https://stackoverflow.com/questions/38431005/how-to-yii2-faker-database-relation

###------POST authorize----------
        curl -X POST \
                http://weshop-v4.back-end.local.vn/1/authorize \
              -H 'content-type: application/json' \
              -d '{
              "username":"weshop2019",
              "password":"weshop@123"
            }'
      ---> Reponse
      {
          "status": 1,
          "data": {
              "authorization_code": "6211b94a7800ad46ec5758b6ae882cb8",
              "expires_at": 1550136755
          }
      }

###------POST accesstoken----------

      curl -X POST \
          http://weshop-v4.back-end.local.vn/1/accesstoken \
      -H 'content-type: application/json' \
      -d '{
            "authorization_code": "6211b94a7800ad46ec5758b6ae882cb8"
         }'

       ---> Reponse

       {
           "status": 1,
           "data": {
               "access_token": "5c1cab2256d8dd76490b3497afa54795",
               "expires_at": 1555320752
           }
       }

###------Get me----------

      curl -X GET \
     -H "X-Access-Token: 6b26ec4b8c3e52aa3bdbbd1d3d0a1224" \
     -G  'http://weshop-v4.back-end.local.vn/1/me'

     ---> Reponse

    {
        "status": 1,
        "data": {
            "id": 2,
            "username": "ws-global",
            "email": "phuchc@peacesoft.net",
            "status": 10,
            "created_at": 1550284712,
            "updated_at": 1550284712
        }
    }

    Optional sort/filter params: page,limit,order,search[name],search[email],search[id]... etc

 #--------------- Installing using Docker -----------------
 https://github.com/yiisoft/yii2-app-advanced/blob/master/docs/guide/start-installation.md

  Install the application dependencies

  docker-compose run --rm backend composer install
  Initialize the application by running the init command within a container

  docker-compose run --rm backend /app/init
  Add a database service like and adjust the components['db'] configuration in common/config/main-local.php accordingly.

      'dsn' => 'mysql:host=mysql;dbname=yii2advanced',
      'username' => 'yii2advanced',
      'password' => 'secret',
  Docker networking creates a DNS entry for the host mysql available from your backend and frontend containers.

  If you want to use another database, such a Postgres, uncomment the corresponding section in docker-compose.yml and update your database connection.

      'dsn' => 'pgsql:host=pgsql;dbname=yii2advanced',
  For more information about Docker setup please visit the guide.

  Run the migrations

  docker-compose run --rm backend yii migrate
  Start the application

  docker-compose up -d
  Access it in your brower by opening

  frontend: http://127.0.0.1:20080
  backend: http://127.0.0.1:21080



  #-----------------------------
  http://paginaswebpublicidad.com/questions/1365/tai-sao-chi-co-the-co-mot-cot-timestamp-voi-current-timestamp-trong-menh-de-default

  INSERT INTO address (village,created_date) VALUES (100,null);
  UPDATE address SET village=101 WHERE village=100;

           CREATE TABLE `address` (
            `id` int(9) NOT NULL AUTO_INCREMENT,
            `village` int(11) DEFAULT NULL,
              `created_date` timestamp default '0000-00-00 00:00:00',

              -- Since explicit DEFAULT value that is not CURRENT_TIMESTAMP is assigned for a NOT NULL column,
              -- implicit DEFAULT CURRENT_TIMESTAMP is avoided.
              -- So it allows us to set ON UPDATE CURRENT_TIMESTAMP on 'updated_date' column.
              -- How does setting DEFAULT to '0000-00-00 00:00:00' instead of CURRENT_TIMESTAMP help?
              -- It is just a temporary value.
              -- On INSERT of explicit NULL into the column inserts current timestamp.

          -- `created_date` timestamp not null default '0000-00-00 00:00:00', // same as above

          -- `created_date` timestamp null default '0000-00-00 00:00:00',
          -- inserting 'null' explicitly in INSERT statement inserts null (Ignoring the column inserts the default value)!
          -- Remember we need current timestamp on insert of 'null'. So this won't work.

          -- `created_date` timestamp null , // always inserts null. Equally useless as above.

          -- `created_date` timestamp default 0, // alternative to '0000-00-00 00:00:00'

          -- `created_date` timestamp,
          -- first 'not null' timestamp column without 'default' value.
          -- So implicitly adds DEFAULT CURRENT_TIMESTAMP and ON UPDATE CURRENT_TIMESTAMP.
          -- Hence cannot add 'ON UPDATE CURRENT_TIMESTAMP' on 'updated_date' column.


             `updated_date` timestamp null on update current_timestamp,

            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8UPDATE address SET village=101 WHERE village=100



   ###--------------------generration Data base --------------------------
   #----------Sinh tat ca lai giu lieu------------
   php yii fixture/generate-all --count=100    ---> để sinh 100 bản ghi từ 0 - 99
   php yii fixture/load "*"   ---> để lưu tất cả các bảng giữ liệu vừa sinh vào database

   php yii fixture/load Warehouse  ---> load data fixed gen for tables Warehouse


   #---------Sinh theo tung bang vi du bang user co check ca khoa ngoai -----------------
   php yii fixture/generate user --count=100
   php yii fixture/load User

   #--------------- Tự sinh 5 User của Viet Nam -----------------
   yii fixture/generate user --count=5 --language=vi_VN


   ####-----------PHP UNEST TEST POST-------------
   API GET : http://weshop-v4.front-end.local.vn/api/posts
   API GET POST DETAIL : http://weshop-v4.front-end.local.vn/api/posts/1
           <response>
           <item>
           <id>1</id>
           <title>dsdsdasds</title>
           <text>dsadsad</text>
           <status>0</status>
           <created_at>0</created_at>
           <updated_at>0</updated_at>
           </item>
           </response>


  ### ----------- Access POST Front-End--------------
  http://weshop-v4.front-end.local.vn/posts/index



  ### PREPARING FOR THE TESTS
  ###Follow these steps to prepare for the tests:

  cd frontend/

  ###  Create yii2_basic_tests or other test database and update it by applying migrations:
  tests/bin/yii migrate
  ### You can specify your test database options in configuration file /config/test_db.php.

  ##   Codeception uses autogenerated Actor classes for own test suites. Build them with this command:
  composer exec codecept build

  $ codecept build

          Building Actor classes for suites: acceptance, api, functional, unit
           -> AcceptanceTesterActions.php generated successfully. 0 methods added
          frontend\tests\AcceptanceTester includes modules: WebDriver, Yii2
           -> ApiTesterActions.php generated successfully. 0 methods added
          frontend\tests\ApiTester includes modules: REST, PhpBrowser, Yii2
          ApiTester.php created.
           -> FunctionalTesterActions.php generated successfully. 0 methods added
          frontend\tests\FunctionalTester includes modules: Filesystem, Yii2
           -> UnitTesterActions.php generated successfully. 0 methods added
          frontend\tests\UnitTester includes modules: Yii2, Asserts


  #### RUNNING UNIT AND FUNCTIONAL TESTS
  ###We can run any types of the application's tests right now:

  # run all available tests
  composer exec codecept run

  $ codecept run

      Codeception PHP Testing Framework v2.3.6
      Powered by PHPUnit 5.7.21-17-g4eba33748 by Sebastian Bergmann and contributors.

      Frontend\tests.acceptance Tests (9) -----------------------------------------------------------------------------------------------------------------------------------------------------------
      E AboutCest: Ensure that about works
      E ContactCest: Ensure that contact page works
      E ContactCest: Contact form can be submitted
      E HomeCest: Check home
      E LoginCest: Ensure that login works
      E PostsCest: Ensure that post index page works
      E PostsCest: Ensure that post view page works
      E PostsCest: Ensure that post create page works
      E PostsCest: Test delete
      -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

      Frontend\tests.api Tests (6) ------------------------------------------------------------------------------------------------------------------------------------------------------------------
      E PostsCest: Test get all
      E PostsCest: Test get one (0.02s)
      E PostsCest: Test get not found (0.00s)
      E PostsCest: Test create (0.00s)
      E PostsCest: Test update (0.00s)
      E PostsCest: Test delete (0.00s)
      -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

      Frontend\tests.functional Tests (18) ----------------------------------------------------------------------------------------------------------------------------------------------------------
      E AboutCest: Check about
      ✔ ContactCest: Check contact (0.63s)
      E ContactCest: Check contact submit no data (0.12s)
      E ContactCest: Check contact submit not correct email (0.00s)
      E ContactCest: Check contact submit correct data (0.01s)
      E HomeCest: Check open (0.00s)
      E LoginCest: Check empty
      E LoginCest: Check wrong password
      E LoginCest: Check valid login
      E SignupCest: Signup with empty fields (0.00s)
      E SignupCest: Signup with wrong email (0.00s)
      E SignupCest: Signup successfully (0.00s)
      E PostsCest: Test index (0.00s)
      E PostsCest: Test view (0.00s)
      E PostsCest: Test create invalid (0.00s)
      E PostsCest: Test create valid (0.00s)
      E PostsCest: Test update (0.00s)
      E PostsCest: Test delete (0.00s)


  # run functional tests
  composer exec codecept run functional

  # run unit tests
  composer exec codecept run unit

  # ----------------
  php -S 127.0.0.1:8880 -t api/web

  ###-------Run------
   php -S 127.0.0.1:8980 -t backend/web
   vendor/bin/codecept run -- -c backend
   $ vendor/bin/codecept run --steps --debug -- -c backend




  ### ----------Modul Card test-----------
  http://weshop-v4.front-end.local.vn/cart/index


  ###---------Selenium--------------------
  cd ~/Desktop
  $ java -jar selenium-server-standalone-3.141.59.jar

  ###---------user + pass--------------
  id_user+@123456789
  "username": "nghiem.manh",
  "password": "1@123456789"



###-----------------yii2-authclient---------------------
    https://github.com/yiisoft/yii2-authclient/blob/master/docs/guide/quick-start.md
    Adding widget to login view
    There's ready to use [[yii\authclient\widgets\AuthChoice]] widget to use in views:

    <?= yii\authclient\widgets\AuthChoice::widget([
         'baseAuthUrl' => ['site/auth'],
         'popupMode' => false,
    ]) ?>

    $_GET = [
        'authclient' => 'google',
        'state' => 'a817cac41d5065eac2cbacc7b59ed57000180f5c66e231a6c2b7e5be7f142f4a',
        'code' => '4/BAGWaSKpLRogBcCTOn76CNU16YW6jzoilUt8vYXhVZ63bgshAo19_d0fkyvF8bihYl3Z10657vopcteYGKhf0m8',
        'scope' => 'email profile https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
    ];

     https://packagist.org/packages/hbhe/yii2-authclient



###--------IP Filter----------
C:\xampp\htdocs\weshop-v4.0-api\vendor\johnsnook\yii2-ip-filter\models\Visitor.php
https://www.yiiframework.com/extension/johnsnook/yii2-ip-filter

 public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                if($this->ip !== '127.0.0.1'){
                    if (($info = $this->getIpInfo($this->ip)) !== null) {
                        $this->city = $info->city;
                        $this->region = $info->region;
                        $country = Country::findOne(['code' => $info->country]);
                        $this->country = $country->name;
                        if ($info->loc) {
                            $this->latitude = floatval(explode(',', $info->loc)[0]);
                            $this->longitude = floatval(explode(',', $info->loc)[1]);
                        }
                        $this->organization = $info->org;
                    }
                    if (($pcheck = $this->getProxyInfo($this->ip)) !== null) {
                        $this->proxy = ($pcheck->proxy === 'yes' ? $pcheck->type : 'no');
                        if ($this->proxy !== 'no') {
                            $this->is_blacklisted = true;
                        }
                    }
                }else {
                    $this->city = 'Ha Noi';
                    $this->region = 'Viet Nam';
                    $this->country = 'Viet Nam';
                    $this->latitude = '21.028511';
                    $this->longitude = '105.804817';
                    $this->organization = 'Weshop';
                    $this->proxy = 'no';
                    $this->is_blacklisted = true;
                }

            }
            return true;
        }
        return false;
    }


 ####-----------Tat ca cac lenh chay khi tao db moi----------------------

    #####--------Create all Tabales-----------------
    php yii migrate/fresh
    php yii  migrate/up

    ####-----------Load data fixed test------------
    php yii fixture/load "*"

    # --------------i18n------------------------
    php yii i18n-migrate

    #------------RBAC authen-----------------------
    php yii rbac/migrate
    php yii fixture/load User
    php yii rbac/create-default

    ####----------------Automaintion test done --------------------------------
    $ php -S 127.0.0.1:8880 -t api/web
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


#####---------Load Db Test--------------
php yii fixture/load "Package,PackageItem,Order,Product"

####--------------- Document migration --------------------------
https://www.yiiframework.com/doc/guide/1.1/en/database.migration

 #collect coverage for all tests
 vendor/bin/codecept run -- --coverage-html --coverage-xml

 #------------Test Units------------

 Generators
 There are plenty of useful Codeception commands:

 generate:cest suite filename - Generates a sample Cest test
 generate:test suite filename - Generates a sample PHPUnit Test with Codeception hooks
 generate:feature suite filename - Generates Gherkin feature file
 generate:suite suite actor - Generates a new suite with the given Actor class name
 generate:scenarios suite - Generates text files containing scenarios from tests
 generate:helper filename - Generates a sample Helper File
 generate:pageobject suite filename - Generates a sample Page object
 generate:stepobject suite filename - Generates a sample Step object
 generate:environment env - Generates a sample Environment configuration
 generate:groupobject group - Generates a sample Group Extension




  php vendor/bin/codecept generate:scenarios
  php vendor/bin/codecept generate:cest acceptance Signin


  #-----------Cache Yii-------------
  # Page Cache
  https://www.yiiframework.com/doc/api/2.0/yii-filters-pagecache

  PageCache implements server-side caching of whole pages.

  It is an action filter that can be added to a controller and handles the beforeAction event.

  To use PageCache, declare it in the behaviors() method of your controller class. In the following example the filter will be applied to the index action and cache the whole page for maximum 60 seconds or until the count of entries in the post table changes. It also stores different versions of the page depending on the application language.

  public function behaviors()
  {
      return [
          'pageCache' => [
              'class' => 'yii\filters\PageCache',
              'only' => ['index'],
              'duration' => 60,
              'dependency' => [
                  'class' => 'yii\caching\DbDependency',
                  'sql' => 'SELECT COUNT(*) FROM post',
              ],
              'variations' => [
                  \Yii::$app->language,
              ]
          ],
      ];
  }

 # http Cache
 https://www.yiiframework.com/doc/api/2.0/yii-filters-httpcache
 HttpCache implements client-side caching by utilizing the Last-Modified and ETag HTTP headers.

 It is an action filter that can be added to a controller and handles the beforeAction event.

 To use HttpCache, declare it in the behaviors() method of your controller class. In the following example the filter will be applied to the index action and the Last-Modified header will contain the date of the last update to the user table in the database.

 public function behaviors()
 {
     return [
         [
             'class' => 'yii\filters\HttpCache',
             'only' => ['index'],
             'lastModified' => function ($action, $params) {
                 $q = new \yii\db\Query();
                 return $q->from('user')->max('updated_at');
             },
 //            'etagSeed' => function ($action, $params) {
 //                return // generate ETag seed here
 //            }
         ],
     ];
 }


 https://www.yiiframework.com/doc/api/2.0/yii-db-querybuilder#getColumnType()-detail


#-----------Purchase-------------
php yii fixture/load PurchasePaymentCard
php yii fixture/load ListAccountPurchase


####---------Warning: session_regenerate_id(): Session object destruction failed in------
https://stackoverflow.com/questions/27438806/warning-session-regenerate-id-session-object-destruction-failed-in/27453468
 Line 300 vendor/yiisoft/yii2/web/Session.php


##------API TEST PHU THU DANH MUC--------------
php yii fixture/load "Category,CategoryGroup"
API : http://weshop-v4.back-end.local.vn/test/get-custom-fee
{
    "price": 400,
    "quantity": 7,
    "weight": 0.5,
    "isNew": false,
    "cate_id": 11,
    "rules": [
        {
            "conditions": [
                {
                    "value": 0,
                    "key": "price",
                    "type": "int",
                    "operator": ">"
                }
            ],
            "fee": 10,
            "unit": "quantity",
            "type_fee": "%"
        }
    ],
    "custom_fee": 280,
    "message": "Lấy custom fee thành công"
}


# --------Test Tracking Code--------
$ php yii fixture/load "TrackingCode"


# --------- status active btn purchase
READY_PURCHASE or PURCHASE_PART or ( PURCHASING and ( PURCHASE_ASSIGNEE_ID = user_id or user_role = 'superAdmin,master_operation' ))

#-------------- Giả lập dữ liệu draft package

1.	Tạo 1 lô tracking
2.	Chạy job giả lập dữ liệu extension      		php yii box-me/emulator-extension manifest_code token
3.	Chạy job merge extension						php yii box-me/merge-extension-us-sending
4.	Get Type Tracking
5.	Chạy job giả lập dữ liệu callback				php yii box-me/emulator-callback-detail manifest_id


#----------Tables packet tam----------
1. draft_extension_tracking_map : Nhận dữ liệu các tracking lấy được từ extension : ( data làm dữ liệu để đánh dấu hàng tách hàng gom)

2. draft_data_tracking : Nhận dữ liệu được map nối từ 2 bảng tracking_code (us sending : Tạo lô)
                      và draft_extension_tracking_map.
   2.0 Tạo Lô ( Ko xử lý trường hợp tạo lô nhầm và tạo lại) --> bắt log chắc chắn + ko cho xóa lô --> quét tracking Trùng để ko cho tạo nhầm lô
   2.1 Chạy Crontab tự động map 2 bảng với nhau và cho vào hàng đợi nếu quá nhiều tracking
   2.3 Nếu Extension có dữ liệu mới do update bằng tay hoặc yêu cầu chạy lại thì map lại
       Hoặc Phải chạy lại đến khi không có "hàng gom trong hàng tách " ,
            Chạy lại đến khi Chí có nhãn : NORMAL / UNKNOW / TÁCH hoặc nhân viên thao tác stop tiến trình thì dừng lại chuyển bước 3

3. draft_boxme_tracking (hàng boxme đã nhận) : Nhận dữ liệu được đối chiếu từ api detail
                     với bảng draft_data_tracking để kiểm tra bên boxme đã nhận được những tracking nào và trạng thái từng tracking Closed / Open của boxme

   ---> Gửi API BOXME Kiểm hàng - đã đánh dấu hàng tách , gom  ( gửi email + api packing)
            https://www.getpostman.com/collections/d0cf6d7f64d4d96029cd
            GET DETAIL PACKING : https://wms.boxme.asia/v1/packing/detail/RVSID-003/?page=1
            CREATE PACKING LIST API (LIVE) : https://s.boxme.asia/v1/packing/packing/create
   3.0 Yêu cầu Boxme có mã nào trên gói hàng phải quét tất
   3.2 . Cho vào lô vào hàng đợi chạy Merger Tracking ( Đối chiếu tracking done) với BoxMe
   3.3 . Với Lô Done :  Xuất API cho boxme kiểm hàng --> Có thể gưi email hoặc tạo lại qua API BOXME Chuyển qua bước 4

4. draft_missing_tracking (hàng thiếu) : Nhận dữ liệu khi mà bên boxme không nhận được tracking trong bảng draft_data_tracking
   4.1 . Đánh dấu tracking, Order nào nếu biết là hàng thiếu
   4.2 . Nếu biết được Tracking , máp với đơn nào thuộc KH nào Move sang bảng Package + Packe Item để xuất Kho , va tạo mã vận đơn tại màn hình Shipment

5. draft_wasting_tracking (hàng thừa) : Nhận dữ liệu khi mà bên boxme nhận được tracking
             nhưng đối chiếu draft_data_tracking hoặc draft_boxme_tracking lại không có
   5.1

6. draft_package_item (hàng đầy đủ): Nhận dữ liệu khi mà bên boxme nhận được tracking
                 và đối chiếu draft_data_tracking hoặc draft_boxme_tracking có tracking.



        /*
        'session' => array(
            'class' => 'yii\web\CacheSession',
            'cache' => 'sessionCache',
        ),
        'sessionCache' => array(
            'class' => 'yii\caching\MemCache',
        ),
        'cache' => [
           'class' => 'yii\caching\MemCache',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        */


               'mailer' => [
                    'class' => 'yii\swiftmailer\Mailer',
                    'viewPath' => '@common/mail',
                    // send all mails to a file by default. You have to set
                    // 'useFileTransport' to false and configure a transport
                    // for the mailer to send real emails.
                    'useFileTransport' => false,
                    'transport' => [
                        'class' => 'Swift_SmtpTransport',
                        'host' => 'smtp.gmail.com',
                        'username' => 'no-reply-wsvn@weshop.com.vn',
                        'password' => 'n*3NGa9&@WS2019now',
                        'port' => '587',
                        'encryption' => 'tls',
                    ],
                ],
            ],

            no-reply-dev-wsvn@weshop.com.vn / a>d2&XK4@nowWeshop2019!@#

###----------------- Event Component For Gate Ebay / Amazon --------------------------
1. https://www.yiiframework.com/doc/guide/2.0/en/concept-events
   https://www.yiiframework.com/doc/api/2.0/yii-web-userevent

2. ERROR :  moontoast/math 1.1.2 requires ext-bcmath * -> the requested PHP extension bcmath is missing from your system
   For any version in php Centos use
   This solution worked for me
   yum install php-bcmath

   yum search bcmath

[root@Weshop weshop-v4.0-api]# yum search bcmath
Loaded plugins: fastestmirror, replace
Loading mirror speeds from cached hostfile
 * base: centos-hn.viettelidc.com.vn
 * epel: mirror.horizon.vn
 * extras: centos-hn.viettelidc.com.vn
 * remi-php72: remi.schlundtech.de
 * updates: centos-hn.viettelidc.com.vn
======================================================================================================== N/S Matched: bcmath =========================================================================================================
php-bcmath.x86_64 : A module for PHP applications for using the bcmath library


yum install php-bcmath.x86_64

#Google Analytich Dev
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-140658371-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-140658371-1');
</script>

#-------------- GA User Exploer : Check xem doanh thu đến từ ông nao nhiều nhất--------------------
http://prntscr.com/nsr24f

 
### --------------Install OCI8 Oracle----------------
https://viblo.asia/p/cai-dat-oracle-driver-vao-php-module-tren-window-bJzKmGekl9N
https://blogs.oracle.com/opal/installing-xampp-for-php-and-oracle-database
=======
#### -------------gRPC--------------

https://stackoverflow.com/questions/50222772/installing-grpc-for-localhost
accorging to your php version (My PHP version is 7.2.6 and x86 architecture xampp) I downloaded php_grpc-1.10.0-7.2-ts-vc15-x86.zip
after downloading extract zip file copy php_grpc.dll in e.g C:\xampp\php\ext folder
open php.ini under Dynamic extentions add extension=grpc
restart your apache server for checking open up cmd and type php -m it will show you all the extension enabled.

$ composer require "grpc/grpc:^v1.7.0"
$ composer require "google/protobuf:^v3.4.0"
$ composer require "protobuf-php/protobuf-plugin"

protoc --version
libprotoc 3.6.1

#####------------------------------
composer require spiral/php-grpc  : https://github.com/spiral/php-grpc
https://viblo.asia/p/grpc-va-ung-dung-no-trong-microservices-ORNZqo8N50n
###----------------PHP Generated Code----------------
https://developers.google.com/protocol-buffers/docs/reference/php-generated

### protoc --proto_path=boxme --php_out=build/gen accountingboxme.proto
#-------------------------
https://github.com/protocolbuffers/protobuf/tree/master/php 

## ------------------ for windown -------------
src: https://github.com/protocolbuffers/protobuf/releases
64: https://github.com/protocolbuffers/protobuf/releases/download/v3.8.0/protoc-3.8.0-win64.zip
32: https://github.com/protocolbuffers/protobuf/releases/download/v3.8.0/protoc-3.8.0-win32.zip

=> download and copy => C:\protoc
=> set env path => C:\protoc\bin

/*
protoc --php_out=boxme proto/accounting.proto
protoc --php_out=common\grpc\boxme proto/accounting.proto
protoc --proto_path=common\grpc\boxme --php_out=build/gen proto/accounting.proto
protoc --proto_path=protobufboxme --php_out=build/gen proto/accounting.proto
protoc --proto_path=protobufboxme --php_out=build/gen proto/user.proto

protoc -I=.  --php_out=./proto/  --plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin ./proto/Greeter.proto

protoc -I=.  --php_out=./protobufboxme/  --plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin ./proto/accounting.proto
protoc -I=.  --php_out=./protobufboxme/  --plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin ./proto/user.proto
protoc -I=.  --php_out=./protobufboxme/  --plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin ./proto/Seller.proto
*/

protoc --php_out=./protobufboxme ./proto/accounting.proto
protoc --php_out=./protobufboxme ./proto/user.proto
protoc --php_out=./protobufboxme ./proto/Seller.proto
protoc --php_out=./protobufboxme ./proto/courier.proto


##----------------Call test grpc boxme-----------------------
https://njp.io/grpcc-a-simple-command-line-client-for-grpc-services/

$ npm install -g grpcc

$ grpcc --proto proto/accounting.proto --address 206.189.94.203:50054 -i

Connecting to Accouting.Accouting on 206.189.94.203:50054. Available globals:

  client - the client connection to Accouting
    getListMerchantById (GetListMerchantByIdRequest, callback) returns GetListMerchantByIdResponse

  printReply - function to easily print a unary call reply (alias: pr)
  streamReply - function to easily print stream call replies (alias: sr)
  createMetadata - convert JS objects into grpc metadata instances (alias: cm)
  printMetadata - function to easily print a unary call's metadata (alias: pm)

Accouting@206.189.94.203:50054> (node:12068) DeprecationWarning: grpc.load: Use the @grpc/proto-loader module with grpc.loadPackageDefinition instead



###--------------http 2---------------
https://inside-out.xyz/technology/how-to-enable-http-2-in-centos-7.html

####---------------Docker Yii2 --------------
https://github.com/dmstr/docker-php-yii2


$ grpcc --proto proto/Greeter.proto --address 127.0.0.1:50051 -i

$ grpcc --proto proto/accounting.proto --address 206.189.94.203:50054 -i
 
$ let erl = client.getListMerchantById({UserId: 23 , CountryCode: 'VN'},printReply)
Accouting@206.189.94.203:50054>
{
  "Error": false,
  "Data": [
    {
      "UserId": 23,
      "CountryCode": "VN",
      "HomeCurrency": "VND",
      "UserLevel": 2,
      "BalancePvc": -105639628.29,
      "BalanceCod": 0,
      "Provisional": 0,
      "Freeze": 4007285.65,
      "Quota": 900000000,
      "MoneyAvailable": 790353086.06,
      "BalanceConfig": 0
    }
  ],
  "Message": "Success"
}
 
 
 $ grpcc --proto proto/user.proto --address 206.189.94.203:50053 -i
 
   client - the client connection to UserService
     signUp (SignUpRequest, callback) returns SignUpResponse
 
   printReply - function to easily print a unary call reply (alias: pr)
   streamReply - function to easily print stream call replies (alias: sr)
   createMetadata - convert JS objects into grpc metadata instances (alias: cm)
   printMetadata - function to easily print a unary call's metadata (alias: pm)
 
 
 let user = client.signUp({user_id: 22 ,email:adv.globalmedia2@gmail.com ,username:'adv.globalmedia2' ,fullname:'Jackly Hoang' , invite_code:5 ,is_active:9},printReply)
 
 let cre = client.SignUp({country: 'VN' ,email:'adv.globalmedia2@gmail.com' ,currency:'VND' ,fullname:'Jackly Hoang' , password1:'weshop@123' , password2:'weshop@123' ,phone:'0972607988', platform_user:'WESHOP' , platform_user : 22},printReply)
   
 ###------------------
 https://www.swoole.com/
 #!/bin/bash
 pecl install swoole
 pear install pecl/swoole
 
 yum install gcc 
 yum update
 
 #---CentOS 6 gcc 4.8-----
 cd /usr/local/src      
 wget http://ftp.gnu.org/gnu/gcc/gcc-7.1.0/gcc-7.1.0.tar.bz2                   #  http://ftp.gnu.org/gnu/gcc)
 tar -jxvf gcc-7.1.0.tar.bz2  
 --------------------- 
https://blog.csdn.net/qq_24849765/article/details/75893393  


##-----config GRPC------
composer dump-autoload
composer validate
composer gen-proto  # 
"autoload": {
    "psr-4": { 
      "": "gen"  // folder ren code
    }
  },
  
  
#------- Protoc Install On Window -------------
src: https://github.com/protocolbuffers/protobuf/releases
64: https://github.com/protocolbuffers/protobuf/releases/download/v3.8.0/protoc-3.8.0-win64.zip
32: https://github.com/protocolbuffers/protobuf/releases/download/v3.8.0/protoc-3.8.0-win32.zip

=> download and copy => C:\protoc
=> set env path => C:\protoc\bin

https://github.com/protocolbuffers/protobuf/releases
https://github.com/protocolbuffers/protobuf/releases/download/v3.8.0/protoc-3.8.0-win64.zip


## courier.proto  GRPC phần tính phí và tạo đơn 
  IP sandbox : 206.189.94.203:50056 
  JSON trên sandbox nhé :
  https://dpaste.de/GeL5
  Token 424de06ca6d2650167b3bdb4fae6c7b451d20ebfcd31650649bde2dc7e6e178e
   
   
  ### Tich hop tao don boxme-dropship
  Indo thì nhập zipcode
  VN thì nhập tỉnh thành quận huyện
  
  Thinh Nguyen, [16.06.19 22:47]
  riêng với dịch vụ dropship từ US thì có mấy lưu ý : 
  - order_type: dropship
  - sản phẩm phải được đồng bộ sang Boxme trước khi tạo đơn


#------ORACLE_HOME------------ # https://www.oratable.com/oracle-home-path/
On Window
echo %ORACLE_HOME%
D:\instantclient-basic-nt-18.5.0.0.0dbru\instantclient_18_5

ORACLE_HOME=D:\Oracle 19- All\instantclient-basic-windows.x64-19.3.0.0.0dbru\instantclient_19_3

On Unix/Linux: type

env | grep ORACLE_HOME


How to set ORACLE_HOME

For the current runtime session, you can set ORACLE_HOME with a single command:

On Windows:

D:\>set ORACLE_HOME=C:\oraclexe\app\oracle\product\10.2.0\server

D:\>echo %ORACLE_HOME%
C:\oraclexe\app\oracle\product\10.2.0\server

D:\>
On Unix/Linux:

export ORACLE_HOME=/app/oracle/product/10.2.0/server
This value will be wiped off when you close the current command line session. To set it as a global environment variable in Windows:

Go to Control Panel -> System -> Advanced.
Click on button “Environment Variables”. This will open a window with two sets of variables – User and System. User variables are visible to your login only, while system variables are visible to anyone else who uses the system.
Choose “New” to create ORACLE_HOME variable as either User or System variable, depending on how you want its visibility.
Set its value to the Oracle directory.
Click OK to save.
Verify through a new command line session that the value has been set correctly.

