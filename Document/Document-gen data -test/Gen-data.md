### ---------Document + command-------------
https://www.yiiframework.com/extension/yiisoft/yii2-faker/doc/api/2.1/yii-faker-fixturecontroller
https://github.com/fzaninotto/Faker
https://github.com/yiisoft/yii2-faker/blob/master/docs/guide/basic-usage.md


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

yii fixture/generate category  --count=5 --language=Vi_VN
yii fixture/generate category_custom_policy  --count=5 --language=Vi_VN
yii fixture/generate order --count=5 --language=VI_VN


yii fixture/generate-all --fixtureDataPath='@common/fixtures/data/components'



# --------------

INSERT INTO `system_country` VALUES (1, 'Việt Nam', 'VN', 'VN', 'vi', '1');
INSERT INTO `system_country` VALUES (2, 'Indonesia', 'id', 'id', 'id', '1');
INSERT INTO `system_country` VALUES (3, 'Malaysia', 'my', 'ms', 'en', '0');
INSERT INTO `system_country` VALUES (4, 'Singapore', 'sg', 'sg', 'sg', '0');
INSERT INTO `system_country` VALUES (5, 'Philippine', 'ph', 'ph', 'ph', '0');
INSERT INTO `system_country` VALUES (6, 'United State', 'US', 'US', 'en', '1');
INSERT INTO `system_country` VALUES (7, 'Thai Lan', 'th', 'TH', 'th', '1');
INSERT INTO `system_country` VALUES (8, 'Chad', 'QA', 'QA', 'ba', '1');
INSERT INTO `system_country` VALUES (9, 'Mauritania', 'CA', 'CA', 'oc', '0');
INSERT INTO `system_country` VALUES (10, 'Portugal', 'DM', 'DM', 'et', '1');

#
INSERT INTO `system_currency` VALUES (1, 'VNĐ', 'vnđ', 'đ', '1');
INSERT INTO `system_currency` VALUES (2, 'RMB', 'rmb', 'rmb', '1');
INSERT INTO `system_currency` VALUES (3, 'USD', 'usd', '$', '1');
INSERT INTO `system_currency` VALUES (4, 'JPY', 'JPY', '￥', '1');
INSERT INTO `system_currency` VALUES (5, 'DOP', 'BTN', 'RON', '0');
INSERT INTO `system_currency` VALUES (6, 'RON', 'CZK', 'ZMW', '0');
INSERT INTO `system_currency` VALUES (7, 'EUR', 'MDL', 'CRC', '0');
INSERT INTO `system_currency` VALUES (8, 'KYD', 'PAB', 'XCD', '0');
INSERT INTO `system_currency` VALUES (9, 'BRL', 'ZMW', 'LKR', '1');
INSERT INTO `system_currency` VALUES (10, 'KGS', 'MXN', 'COP', '1');
