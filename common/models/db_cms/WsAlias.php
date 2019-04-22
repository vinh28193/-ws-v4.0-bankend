<?php

namespace common\models\db_cms;

use Yii;

/**
 * This is the model class for table "ws_alias".
 *
 * @property int $id
 * @property string $name
 * @property string $name_icon
 * @property string $url
 * @property string $type EBAY/AMZ/HOME
 * @property int $store_id
 * @property int $ws_page_item_id
 * @property string $alias_categories
 *
 * @property WsPageItem $wsPageItem
 * @property WsAliasItem[] $wsAliasItems
 */
class WsAlias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ws_alias';
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
            [['store_id', 'ws_page_item_id'], 'integer'],
            [['name', 'name_icon', 'url'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 10],
            [['alias_categories'], 'string', 'max' => 500],
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
            'name' => 'Name',
            'name_icon' => 'Name Icon',
            'url' => 'Url',
            'type' => 'Type',
            'store_id' => 'Store ID',
            'ws_page_item_id' => 'Ws Page Item ID',
            'alias_categories' => 'Alias Categories',
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
    public function getWsAliasItems()
    {
        return $this->hasMany(WsAliasItem::className(), ['ws_alias_id' => 'id']);
    }
}
