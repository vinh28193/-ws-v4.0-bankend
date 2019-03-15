
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