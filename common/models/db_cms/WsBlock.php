<?php

namespace common\models\db_cms;

use Yii;

/**
 * This is the model class for table "ws_block".
 *
 * @property int $id
 * @property string $portal_logo
 * @property string $name
 * @property string $url
 * @property string $keywords
 * @property string $type LANDING/BLOCK5/BLOCK7/BLOCK8/HOME_SLIDER
 * @property string $content
 * @property string $image_url
 * @property string $image
 * @property int $ws_page_item_id
 *
 * @property WsPageItem $wsPageItem
 * @property WsCategoryGroup[] $wsCategoryGroups
 * @property WsImageGroup[] $wsImageGroups
 * @property WsProductGroup[] $wsProductGroups
 */
class WsBlock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ws_block';
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
            [['content'], 'string'],
            [['ws_page_item_id'], 'integer'],
            [['portal_logo', 'name', 'url', 'keywords', 'type', 'image'], 'string', 'max' => 255],
            [['image_url'], 'string', 'max' => 500],
            [['ws_page_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => WsPageItem::className(), 'targetAttribute' => ['ws_page_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'portal_logo' => 'Portal Logo',
            'name' => 'Name',
            'url' => 'Url',
            'keywords' => 'Keywords',
            'type' => 'Type',
            'content' => 'Content',
            'image_url' => 'Image Url',
            'image' => 'Image',
            'ws_page_item_id' => 'Ws Page Item ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsPageItem()
    {
        return $this->hasOne(WsPageItem::className(), ['id' => 'ws_page_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsCategoryGroups()
    {
        return $this->hasMany(WsCategoryGroup::className(), ['ws_block_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsImageGroups()
    {
        return $this->hasMany(WsImageGroup::className(), ['ws_block_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsProductGroups()
    {
        return $this->hasMany(WsProductGroup::className(), ['ws_block_id' => 'id']);
    }
}
