<?php

namespace common\models\db_cms;

use Yii;

/**
 * This is the model class for table "ws_image_group".
 *
 * @property int $id
 * @property string $type BRAND/MAIN_SLIDER/BLOCK
 * @property string $name
 * @property int $store_id
 * @property int $ws_block_id
 * @property int $ws_alias_item_id
 * @property int $ws_page_item_id
 *
 * @property WsImage[] $wsImages
 * @property WsPageItem $wsPageItem
 * @property WsAliasItem $wsAliasItem
 * @property WsBlock $wsBlock
 */
class WsImageGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ws_image_group';
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
            [['store_id', 'ws_block_id', 'ws_alias_item_id', 'ws_page_item_id'], 'integer'],
            [['type'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['ws_page_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => WsPageItem::className(), 'targetAttribute' => ['ws_page_item_id' => 'id']],
            [['ws_alias_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => WsAliasItem::className(), 'targetAttribute' => ['ws_alias_item_id' => 'id']],
            [['ws_block_id'], 'exist', 'skipOnError' => true, 'targetClass' => WsBlock::className(), 'targetAttribute' => ['ws_block_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
            'store_id' => 'Store ID',
            'ws_block_id' => 'Ws Block ID',
            'ws_alias_item_id' => 'Ws Alias Item ID',
            'ws_page_item_id' => 'Ws Page Item ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsImages()
    {
        return $this->hasMany(WsImage::className(), ['ws_image_group_id' => 'id']);
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
    public function getWsAliasItem()
    {
        return $this->hasOne(WsAliasItem::className(), ['id' => 'ws_alias_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsBlock()
    {
        return $this->hasOne(WsBlock::className(), ['id' => 'ws_block_id']);
    }
}
