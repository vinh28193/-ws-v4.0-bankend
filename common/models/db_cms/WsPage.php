<?php

namespace common\models\db_cms;

use Yii;

/**
 * This is the model class for table "ws_page".
 *
 * @property int $id
 * @property string $name
 * @property string $seo_keyword
 * @property string $url
 * @property string $type HOME/EBAY/AMAZON/LANDING
 * @property bool $status
 * @property int $store_id
 * @property string $description
 * @property string $title
 * @property string $image
 *
 * @property WsPageItem[] $wsPageItems
 */
class WsPage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ws_page';
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
            [['status'], 'boolean'],
            [['store_id'], 'integer'],
            [['name', 'seo_keyword', 'description', 'title', 'image'], 'string', 'max' => 255],
            [['url'], 'string', 'max' => 500],
            [['type'], 'string', 'max' => 50],
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
            'seo_keyword' => 'Seo Keyword',
            'url' => 'Url',
            'type' => 'Type',
            'status' => 'Status',
            'store_id' => 'Store ID',
            'description' => 'Description',
            'title' => 'Title',
            'image' => 'Image',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsPageItems()
    {
        return $this->hasMany(WsPageItem::className(), ['ws_page_id' => 'id']);
    }
}
