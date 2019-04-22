<?php

namespace common\models\db_cms;

use Yii;

/**
 * This is the model class for table "ws_category".
 *
 * @property int $id
 * @property string $alias
 * @property string $name
 * @property string $url
 * @property string $local_name
 * @property int $status
 * @property int $parent_id
 * @property int $store_id
 * @property string $image
 * @property int $ws_category_group_id
 * @property string $sort
 * @property string $desc
 *
 * @property WsCategory $parent
 * @property WsCategory[] $wsCategories
 * @property WsCategoryGroup $wsCategoryGroup
 */
class WsCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ws_category';
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
            [['status', 'parent_id', 'store_id', 'ws_category_group_id'], 'integer'],
            [['alias'], 'string', 'max' => 20],
            [['name', 'url', 'local_name', 'image', 'sort', 'desc'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => WsCategory::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'alias' => 'Alias',
            'name' => 'Name',
            'url' => 'Url',
            'local_name' => 'Local Name',
            'status' => 'Status',
            'parent_id' => 'Parent ID',
            'store_id' => 'Store ID',
            'image' => 'Image',
            'ws_category_group_id' => 'Ws Category Group ID',
            'sort' => 'Sort',
            'desc' => 'Desc',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(WsCategory::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsCategories()
    {
        return $this->hasMany(WsCategory::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsCategoryGroup()
    {
        return $this->hasOne(WsCategoryGroup::className(), ['id' => 'ws_category_group_id']);
    }
}
