<?php

namespace common\models\db_cms;

use Yii;

/**
 * This is the model class for table "ws_image".
 *
 * @property int $id
 * @property string $name
 * @property string $origin_src
 * @property string $thumb_src
 * @property string $domain
 * @property string $link
 * @property int $status
 * @property int $ws_image_group_id
 * @property int $sort
 * @property int $ws_category_group_id
 *
 * @property WsImageGroup $wsImageGroup
 * @property WsCategoryGroup $wsCategoryGroup
 */
class WsImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ws_image';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_cms');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'ws_image_group_id', 'sort', 'ws_category_group_id'], 'integer'],
            [['name', 'origin_src', 'thumb_src', 'link'], 'string', 'max' => 255],
            [['domain'], 'string', 'max' => 50],
            [['ws_image_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => WsImageGroup::className(), 'targetAttribute' => ['ws_image_group_id' => 'id']],
            [['ws_category_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => WsCategoryGroup::className(), 'targetAttribute' => ['ws_category_group_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'origin_src' => 'Origin Src',
            'thumb_src' => 'Thumb Src',
            'domain' => 'Domain',
            'link' => 'Link',
            'status' => 'Status',
            'ws_image_group_id' => 'Ws Image Group ID',
            'sort' => 'Sort',
            'ws_category_group_id' => 'Ws Category Group ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsImageGroup()
    {
        return $this->hasOne(WsImageGroup::className(), ['id' => 'ws_image_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsCategoryGroup()
    {
        return $this->hasOne(WsCategoryGroup::className(), ['id' => 'ws_category_group_id']);
    }
}
