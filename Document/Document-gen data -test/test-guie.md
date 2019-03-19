
# --------Run ENV--------
  php -S 127.0.0.1:8880 -t api/web
  
  ###-------Run ENV------
   php -S 127.0.0.1:8980 -t backend/web

###---------Test Report------------
$ vendor/bin/codecept run --report -- -c backend
$ vendor/bin/codecept run --report -- -c api

#####--------Test Unnits---------------------
$ vendor/bin/codecept run api/tests/unit/models/
$ vendor/bin/codecept run api/tests/unit

$ vendor/bin/codecept run backend/tests/unit/models/
$ vendor/bin/codecept run backend/tests/unit


#####--------Test functional--------------------- 
$ vendor/bin/codecept run api/tests/functional
 
$ vendor/bin/codecept run backend/tests/functional

###-----------Test Common-------------------------
$ vendor/bin/codecept run --report -- -c common
