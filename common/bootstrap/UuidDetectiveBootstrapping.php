<?php


namespace common\bootstrap;

use yii\web\Application;
use yii\base\BootstrapInterface;

class UuidDetectiveBootstrapping implements BootstrapInterface
{

    const UUID_HEADER_TOKEN = 'X-Fingerprint-Token';

    /**
     * @param Application $app
     */
    public function bootstrap($app)
    {
        // TODO: Implement bootstrap() method.
    }
}