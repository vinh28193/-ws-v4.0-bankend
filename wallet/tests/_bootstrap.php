<?php

/**
 * STEP 1: composer global require "codeception/codeception=2.1.*" "codeception/specify=*" "codeception/verify=*"
 * STEP 2: from root run ./vendor/bin/codecept -- -c wallet build
 * STEP 3
 *      - Generate Unit : vendor/bin/codecept -- -c wallet generate:test unit path_of_file ( use "/" not "\")
 * @link: https://codeception.com/docs/05-UnitTests
 *      - Generate Functional : vendor/bin/codecept -- -c wallet generate:cest functional path_of_file ( use "/" not "\")
 * @link: https://codeception.com/docs/04-FunctionalTests
 *      - Generate acceptance  : vendor/bin/codecept -- -c wallet generate:cest acceptance  path_of_file ( use "/" not "\")
 * @link:
 * STEP 4:run
 *      - Unit vendor/bin/codecept -- -c wallet run unit path_of_file ( use "/" not "\")
 *      - Functional vendor/bin/codecept -- -c wallet run functional path_of_file ( use "/" not "\")
 *      - Acceptance vendor/bin/codecept -- -c wallet run acceptance path_of_file ( use "/" not "\")
 */
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', __DIR__.'/../../');

require_once YII_APP_BASE_PATH . '/vendor/autoload.php';
require_once YII_APP_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php';
require_once YII_APP_BASE_PATH . '/common/config/bootstrap.php';
require_once __DIR__ . '/../config/bootstrap.php';
