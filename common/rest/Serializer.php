<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 16:35
 */

namespace common\components\rest;

use Yii;

class Serializer extends \yii\rest\Serializer
{

    /**
     * @inheritdoc
     * @param mixed $data
     * @return array|mixed
     */
    public function serialize($data)
    {
        if (is_array($data) && isset($data['data'])) {
            $data['data'] = $this->serialize($data['data']);
            return $data;
        } elseif ($data instanceof \yii\base\BaseObject) {
            return Yii::getObjectVars($data);
        }
        return parent::serialize($data);
    }
}