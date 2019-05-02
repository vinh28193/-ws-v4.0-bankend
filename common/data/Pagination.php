<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-05
 * Time: 10:55
 */

namespace common\data;

use Yii;
use yii\web\Request;

class Pagination extends \yii\data\Pagination
{

    /**
     * @inheritdoc
     * @param string $name
     * @param null $defaultValue
     * @return mixed|null|string
     * @throws \yii\base\InvalidConfigException
     */
    protected function getQueryParam($name, $defaultValue = null)
    {
        if (($params = $this->params) === null) {
            $request = Yii::$app->getRequest();
            $params = $request instanceof Request ? ($request->getIsGet() ? $request->getQueryParams() : $request->getBodyParams()) : [];
        }
        return isset($params[$name]) && is_scalar($params[$name]) ? (($param = $params[$name]) === 'all' ? -1 : $param) : $defaultValue;
    }
}