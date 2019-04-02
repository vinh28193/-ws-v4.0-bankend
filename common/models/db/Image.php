<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "image".
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
        return 'image';
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
            'id' => 'ID',
            'store_id' => 'Store ID',
            'reference' => 'Reference',
            'reference_id' => 'Reference ID',
            'base_path' => 'Base Path',
            'name' => 'Name',
            'full_path' => 'Full Path',
            'width' => 'Width',
            'height' => 'Height',
            'quality' => 'Quality',
            'size' => 'Size',
            'type' => 'Type',
            'is_uploaded' => 'Is Uploaded',
            'status' => 'Status',
            'uploaded_by' => 'Uploaded By',
            'uploaded_at' => 'Uploaded At',
            'uploaded_from_ip' => 'Uploaded From Ip',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\ImageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\ImageQuery(get_called_class());
    }
}
