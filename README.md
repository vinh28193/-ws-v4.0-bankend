 
INSTALLATION


```
Step1: Create a database named weshop-global
Step2:Clone the source code
git clone -b master https://github.com/sirinibin/yii2-rest.git

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
           cd /var/www/yii2-rest
            ./yii migrate

Step8:
            point API end point URL to backend
             /var/www/yii2-rest/backend/web


            point frontend URL to frontend
             /var/www/yii2-rest/frontend/web
```

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
              "username":"gstearmit",
              "password":"ngoc875052"
            }'
      ---> Reponse
      {
          "status": 1,
          "data": {
              "authorization_code": "0367d013707b7a1d716500d7487381ce",
              "expires_at": 1550136755
          }
      }      
 
###------POST accesstoken----------
           
      curl -X POST \
          http://weshop-v4.back-end.local.vn/1/accesstoken \
      -H 'content-type: application/json' \
      -d '{
            "authorization_code": "0367d013707b7a1d716500d7487381ce"
         }' 
         
       ---> Reponse
       
       {
           "status": 1,
           "data": {
               "access_token": "95b45bb33a39a25a12c696f1515ec506",
               "expires_at": 1555320752
           }
       }
                   
###------Get me----------
    
      curl -X GET \
     -H "X-Access-Token: 95b45bb33a39a25a12c696f1515ec506" \
     -G  'http://weshop-v4.back-end.local.vn/1/me'   
     
     ---> Reponse
     
     {
         "status": 1,
         "data": {
             "id": 2,
             "username": "gstearmit",
             "email": "gstearmit@gmail.com",
             "status": 10,
             "created_at": 1550128415,
             "updated_at": 1550128415
         }
     } 