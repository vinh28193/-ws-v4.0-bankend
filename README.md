 
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
             /var/www/weshop-v4.0-api/backend/web


            point frontend URL to frontend
             /var/www/weshop-v4.0-api/frontend/web
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
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
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
              "username":"ws-global",
              "password":"ws@2019"
            }'
      ---> Reponse
      {
          "status": 1,
          "data": {
              "authorization_code": "27b721d2e7a3417466728af96df3597d",
              "expires_at": 1550136755
          }
      }      
 
###------POST accesstoken----------
           
      curl -X POST \
          http://weshop-v4.back-end.local.vn/1/accesstoken \
      -H 'content-type: application/json' \
      -d '{
            "authorization_code": "27b721d2e7a3417466728af96df3597d"
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
     -H "X-Access-Token: 5c1cab2256d8dd76490b3497afa54795" \
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
   php yii fixture/load "*"                    ---> để lưu tất cả các bảng giữ liệu vừa sinh vào database
   
   #---------Sinh theo tung bang vi du bang user co check ca khoa ngoai -----------------
   php yii fixture/generate user --count=100
   php yii fixture/load User
   
   #--------------- Tự sinh 5 User của Viet Nam -----------------
   yii fixture/generate user --count=5 --language=vi_VN
   
   
   ####-----------PHP UNEST TEST-------------
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