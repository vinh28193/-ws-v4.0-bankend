<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property int $id ID
 * @property int $store_id Store ID reference
 * @property string $reference Reference key
 * @property int $reference_id Reference identity
 * @property string $base_path basePath
 * @property string $name name
 * @property string $full_path tmp name
 * @property int $width saved width
 * @property int $height saved height
 * @property int $quality saved quality
 * @property int $size saved size (mb)
 * @property string $type Image Type (jpg,png)
 * @property int $is_uploaded 1 is form upload
 * @property int $status Status (1:Active;2:Inactive)
 * @property int $uploaded_by Created by
 * @property int $uploaded_at Created at (timestamp)
 * @property string $uploaded_from_ip Updated from ip address
 */
class Image extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%image}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'reference_id', 'width', 'height', 'quality', 'size', 'is_uploaded', 'status', 'uploaded_by', 'uploaded_at'], 'integer'],
            [['reference', 'type'], 'string', 'max' => 100],
            [['base_path', 'name', 'full_path'], 'string', 'max' => 255],
            [['uploaded_from_ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'store_id' => Yii::t('db', 'Store ID'),
            'reference' => Yii::t('db', 'Reference'),
            'reference_id' => Yii::t('db', 'Reference ID'),
            'base_path' => Yii::t('db', 'Base Path'),
            'name' => Yii::t('db', 'Name'),
            'full_path' => Yii::t('db', 'Full Path'),
            'width' => Yii::t('db', 'Width'),
            'height' => Yii::t('db', 'Height'),
            'quality' => Yii::t('db', 'Quality'),
            'size' => Yii::t('db', 'Size'),
            'type' => Yii::t('db', 'Type'),
            'is_uploaded' => Yii::t('db', 'Is Uploaded'),
            'status' => Yii::t('db', 'Status'),
            'uploaded_by' => Yii::t('db', 'Uploaded By'),
            'uploaded_at' => Yii::t('db', 'Uploaded At'),
            'uploaded_from_ip' => Yii::t('db', 'Uploaded From Ip'),
        ];
    }
}
