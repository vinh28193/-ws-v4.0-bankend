<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 16:35
 */

namespace common\rest;

use Yii;

class Serializer extends \yii\rest\Serializer
{

    public $collectionEnvelope = '_items';
    /**
     * @inheritdoc
     * @param mixed $data
     * @return array|mixed
     */
    public function serialize($data)
    {
        if (is_array($data) && count($data) === 3 && isset($data['data'])) {
            $data['data'] = $this->serialize($data['data']);
            return $data;
        }
        return parent::serialize($data);
    }
}