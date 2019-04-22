<?php

namespace common\models\db_cms;

use Yii;

/**
 * This is the model class for table "ws_page_item".
 *
 * @property int $id
 * @property string $name
 * @property int $ws_page_id
 * @property string $type
 * @property bool $status
 * @property int $sort
 *
 * @property WsAlias[] $wsAliases
 * @property WsBlock[] $wsBlocks
 * @property WsCategoryGroup[] $wsCategoryGroups
 * @property WsImageGroup[] $wsImageGroups
 * @property WsPage $wsPage
 * @property WsProductGroup[] $wsProductGroups
 */
class WsPageItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ws_page_item';
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
            [['ws_page_id', 'sort'], 'integer'],
            [['status'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 50],
            [['ws_page_id'], 'exist', 'skipOnError' => true, 'targetClass' => WsPage::className(), 'targetAttribute' => ['ws_page_id' => 'id']],
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
            'ws_page_id' => 'Ws Page ID',
            'type' => 'Type',
            'status' => 'Status',
            'sort' => 'Sort',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsAliases()
    {
        return $this->hasMany(WsAlias::className(), ['ws_page_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsBlocks()
    {
        return $this->hasMany(WsBlock::className(), ['ws_page_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsCategoryGroups()
    {
        return $this->hasMany(WsCategoryGroup::className(), ['ws_page_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsImageGroups()
    {
        return $this->hasMany(WsImageGroup::className(), ['ws_page_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsPage()
    {
        return $this->hasOne(WsPage::className(), ['id' => 'ws_page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsProductGroups()
    {
        return $this->hasMany(WsProductGroup::className(), ['ws_page_item_id' => 'id']);
    }
}
