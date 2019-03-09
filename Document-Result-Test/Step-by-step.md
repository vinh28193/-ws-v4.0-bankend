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