<?php

namespace common\models\db_cms;

use Yii;

/**
 * This is the model class for table "ws_alias_item".
 *
 * @property int $id
 * @property int $ws_alias_id
 * @property string $name
 * @property string $name_icon
 * @property string $desc
 * @property int $sort
 * @property string $type IMG_GROUP/PRODUCTS/CATEGORIES
 * @property string $layout GRID/CATE_IMG/CATE/SLIDER
 * @property bool $is_head
 * @property bool $status
 * @property string $url
 * @property string $image
 * @property string $title
 * @property string $top_product
 *
 * @property WsAlias $wsAlias
 * @property WsCategoryGroup[] $wsCategoryGroups
 * @property WsImageGroup[] $wsImageGroups
 * @property WsProductGroup[] $wsProductGroups
 */
class WsAliasItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ws_alias_item';
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
            [['ws_alias_id', 'sort'], 'integer'],
            [['is_head', 'status'], 'boolean'],
            [['top_product'], 'string'],
            [['name', 'name_icon', 'desc', 'layout', 'url', 'title'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 30],
            [['image'], 'string', 'max' => 500],
            [['ws_alias_id'], 'exist', 'skipOnError' => true, 'targetClass' => WsAlias::className(), 'targetAttribute' => ['ws_alias_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ws_alias_id' => 'Ws Alias ID',
            'name' => 'Name',
            'name_icon' => 'Name Icon',
            'desc' => 'Desc',
            'sort' => 'Sort',
            'type' => 'Type',
            'layout' => 'Layout',
            'is_head' => 'Is Head',
            'status' => 'Status',
            'url' => 'Url',
            'image' => 'Image',
            'title' => 'Title',
            'top_product' => 'Top Product',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsAlias()
    {
        return $this->hasOne(WsAlias::className(), ['id' => 'ws_alias_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsCategoryGroups()
    {
        return $this->hasMany(WsCategoryGroup::className(), ['ws_alias_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsImageGroups()
    {
        return $this->hasMany(WsImageGroup::className(), ['ws_alias_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsProductGroups()
    {
        return $this->hasMany(WsProductGroup::className(), ['ws_alias_item_id' => 'id']);
    }
}
