### ---------Document + command-------------
https://www.yiiframework.com/extension/yiisoft/yii2-faker/doc/api/2.1/yii-faker-fixturecontroller
https://github.com/fzaninotto/Faker
https://github.com/yiisoft/yii2-faker/blob/master/docs/guide/basic-usage.md

###==========Summary===============
In the above, we have described how to define and use fixtures. Below we summarize the typical workflow of running unit tests related with DB:
1.Use yii migrate tool to upgrade your test database to the latest version;
2.Run a test case:
    Load fixtures: clean up the relevant DB tables and populate them with fixture data;
    Perform the actual test;
    Unload fixtures.
3. Repeat Step 2 until all tests finish.


#---------------------------


$ yii fixture/generate user

# generate fixtures from several templates, for example:
yii fixture/generate user profile team

In the code above "users" is template name, after this command run, new file named same as template will be created under the $fixtureDataPath folder. You can generate fixtures for all templates, for example:

$ yii fixture/generate-all

yii fixture/generate-all --count=3
You can specify different options of this command:

# generate fixtures in russian language
$ yii fixture/generate user --count=5 --language=ru_RU

# read templates from the other path
$ yii fixture/generate-all --templatePath=@app/path/to/my/custom/templates

# generate fixtures into other folders
$ yii fixture/generate-all --fixtureDataPath=@tests/unit/fixtures/subfolder1/subfolder2/subfolder3

# generate fixtures in russian language
$ yii fixture/generate user --count=5 --language=ru_RU

# generate several fixtures
$ yii fixture/generate user profile team

# under folder tests\unit\fixtures

data\
    data_fixed\
       
    components\
        fixture_data_file1.php
        fixture_data_file2.php
        ...
        fixture_data_fileN.php
    models\
        fixture_data_file1.php
        fixture_data_file2.php
        ...
        fixture_data_fileN.php
    units\
        fixture_data_file1.php
        fixture_data_file2.php
        ...
        fixture_data_fileN.php 
            
# and so on

# read templates from the other path
php yii fixture/generate-all --templatePath='@app/path/to/my/custom/templates'
php yii fixture/generate-all --templatePath='@common/fixtures/templates'

# generate fixtures into other directory.
php yii fixture/generate-all --fixtureDataPath='@tests/acceptance/fixtures/data'
php yii fixture/generate-all --fixtureDataPath='@common/fixtures/data/components'

---------------Step by Step-----------------
yii fixture/generate user --count=5 --language=VI_VN
yii fixture/generate seller --count=5 --language=VI_VN
yii fixture/generate customer  --count=5 --language=Vi_VN
yii fixture/generate system_state_province --count=5 --language=Vi_VN
yii fixture/generate system_district  --count=20 --language=Vi_VN
yii fixture/generate address  --count=5 --language=Vi_VN

# System country
yii fixture/generate country  --count=5 --language=Vi_VN

yii fixture/generate category  --count=5 --language=Vi_VN
yii fixture/generate category_custom_policy  --count=5 --language=Vi_VN
yii fixture/generate order --count=5 --language=VI_VN


yii fixture/generate-all --fixtureDataPath='@common/fixtures/data/components'

#-------------load all data needs----------------
###  load several fixtures
yii fixture "User, Seller ,Customer , Product , Systemstateprovince , Systemdistrict , Address , Category , Categorycustompolicy , Order "

yii fixture "User , Product , Order "

yii fixture "Address , Seller , Customer , Systemstateprovince , Systemdistrict , Category , Categorycustompolicy"

###----------gen all ---------------
yii fixture/generate "*"  --count=5 --language=VI_VN

####--------Save All ---------
yii fixture/load "*"


### Unloading fixtures
To unload fixture, run the following command:

// unload Users fixture, by default it will clear fixture storage (for example "users" table, or "users" collection if this is mongodb fixture).
yii fixture/unload User

// Unload several fixtures
yii fixture/unload "User, UserProfile"

// unload all fixtures
yii fixture/unload "*"

// unload all fixtures except ones
yii fixture/unload "*, -DoNotUnloadThisOne"



 

