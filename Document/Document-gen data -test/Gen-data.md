https://www.yiiframework.com/extension/yiisoft/yii2-faker/doc/api/2.1/yii-faker-fixturecontroller


$ yii fixture/generate user

//generate fixtures from several templates, for example:
yii fixture/generate user profile team

In the code above "users" is template name, after this command run, new file named same as template will be created under the $fixtureDataPath folder. You can generate fixtures for all templates, for example:

$ yii fixture/generate-all

yii fixture/generate-all --count=3
You can specify different options of this command:

//generate fixtures in russian language
$ yii fixture/generate user --count=5 --language=ru_RU

//read templates from the other path
$ yii fixture/generate-all --templatePath=@app/path/to/my/custom/templates

//generate fixtures into other folders
$ yii fixture/generate-all --fixtureDataPath=@tests/unit/fixtures/subfolder1/subfolder2/subfolder3

//generate fixtures in russian language
$ yii fixture/generate user --count=5 --language=ru_RU

//generate several fixtures
$ yii fixture/generate user profile team


---------------Step by Step-----------------
yii fixture/generate user --count=5 --language=VI_VN
yii fixture/generate customer  --count=5 --language=Vi_VN
yii fixture/generate system_state_province --count=5 --language=Vi_VN
yii fixture/generate address  --count=5 --language=Vi_VN
