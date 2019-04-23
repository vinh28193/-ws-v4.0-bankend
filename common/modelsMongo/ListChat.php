<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "list_chat".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $note
 * @property mixed $content
 * @property mixed $status
 * @property mixed $time_start
 */
class ListChat extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_global_40', 'list_chat'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'note',
            'content',
            'status',
            'time_start',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['note', 'content', 'status', 'time_start'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'note' => 'Note',
            'content' => 'Content',
            'status' => 'Status',
            'time_start' => 'Time Start',
        ];
    }
}
