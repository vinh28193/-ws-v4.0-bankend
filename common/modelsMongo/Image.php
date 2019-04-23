<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "image".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $link_img
 * @property mixed $order_path
 */
class Image extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['weshop_global_40', 'image'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'link_img',
            'order_path',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['link_img', 'order_path'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'link_img' => 'Link Img',
            'order_path' => 'Order Path',
        ];
    }
}
