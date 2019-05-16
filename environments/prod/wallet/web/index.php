<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);
$request_headers = apache_request_headers();
$http_origin = isset($request_headers['Origin'])?$request_headers['Origin']:'';
$allowed_http_origins = array(
    "http://localhost:4200",
    'http://admin.weshop.cms',
    'http://admin.weshop.local',
    'https://admin-uat.weshop.asia',
    "http://cms-backend-v3.weshop.com.vn",
    'http://operation.weshop.local',
    'https://web-uat-v3.weshop.com.vn',
    "http://cms-backend-v3.weshop.com.vn",
    'https://admin-uat.weshop.asia',
    'http://uat-in.weshop.asia',
    'http://uat-my-v3.weshop.asia',
    'https://weshop.com.vn',
    'https://weshop.co.id',
    'https://weshop.my'
);
if (in_array($http_origin, $allowed_http_origins)) {
    @header("Access-Control-Allow-Origin: " . $http_origin);
}
header('Access-Control-Allow-Credentials: true');

(new yii\web\Application($config))->run();
