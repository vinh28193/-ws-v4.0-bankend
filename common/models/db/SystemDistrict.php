<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "system_district".
 *
 * @property int $id ID
 * @property string $name
 * @property string $name_local
 * @property string $name_alias
 * @property int $display_order
 * @property int $province_id
 * @property int $country_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 * @property string $version version 4.0
 */
class SystemDistrict extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_district';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['display_order', 'province_id', 'country_id', 'created_at', 'updated_at', 'remove'], 'integer'],
            [['name', 'name_local', 'name_alias', 'version'], 'string', 'max' => 255],
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
            'name_local' => 'Name Local',
            'name_alias' => 'Name Alias',
            'display_order' => 'Display Order',
            'province_id' => 'Province ID',
            'country_id' => 'Country ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remove' => 'Remove',
            'version' => 'Version',
        ];
    }
}
