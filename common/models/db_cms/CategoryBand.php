<?php

namespace common\models\db_cms;

use Yii;

/**
 * This is the model class for table "{{%category_band}}".
 *
 * @property string $id
 * @property int $storeId
 * @property string $categoryId
 * @property string $page
 * @property bool $status
 * @property string $nameCategory
 */
class CategoryBand extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category_band}}';
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
            [['storeId', 'categoryId', 'page'], 'required'],
            [['storeId'], 'integer'],
            [['status'], 'boolean'],
            [['nameCategory'], 'string'],
            [['categoryId', 'page'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'storeId' => Yii::t('db', 'Store ID'),
            'categoryId' => Yii::t('db', 'Category ID'),
            'page' => Yii::t('db', 'Page'),
            'status' => Yii::t('db', 'Status'),
            'nameCategory' => Yii::t('db', 'Name Category'),
        ];
    }
}
