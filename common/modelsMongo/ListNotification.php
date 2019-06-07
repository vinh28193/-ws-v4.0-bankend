<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "list_notification".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $title
 * @property mixed $body
 * @property mixed $click_action
 * @property mixed $user_id
 * @property mixed $watched
 * @property mixed $created_at
 */
class ListNotification extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_global_40', 'list_notification'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'title',
            'body',
            'click_action',
            'user_id',
            'watched',
            'created_at'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'body', 'click_action', 'user_id', 'watched', 'created_at'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'title' => 'Title',
            'body' => 'Body',
            'click_action' => 'Click Action',
            'user_id' => 'User ID',
            'watched' => 'Watched',
            'created_at' => 'Created At',
        ];
    }
}
