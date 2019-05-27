<?php


namespace landing;

use ReflectionClass;
use Yii;
use frontend\widgets\cms\WeshopBlockWidget;
use common\components\StoreManager;

class LandingWidget extends WeshopBlockWidget
{


    /**
     * @var StoreManager
     */
    private $_website;

    /**
     * @return StoreManager
     */
    public function getWebsite()
    {
        if (!$this->_website) {
            $this->_website = Yii::$app->storeManager;
        }
        return $this->_website;
    }

    public function getViewPath()
    {
        $class = new ReflectionClass($this);

        return dirname($class->getFileName());
    }
}