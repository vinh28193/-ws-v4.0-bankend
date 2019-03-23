<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "system_state_province".
 *
 * @property int $id ID
 * @property int $country_id id nước
 * @property string $name
 * @property string $name_local
 * @property string $name_alias
 * @property int $display_order
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 * @property string $version version 4.0
 */
class SystemStateProvince extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_state_province';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'display_order', 'created_at', 'updated_at', 'remove'], 'integer'],
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
            'country_id' => 'Country ID',
            'name' => 'Name',
            'name_local' => 'Name Local',
            'name_alias' => 'Name Alias',
            'display_order' => 'Display Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remove' => 'Remove',
            'version' => 'Version',
        ];
    }

    public function getSystemDistricts()
    {
        return $this->hasOne(SystemDistrict::className(), ['province_id' => 'id']);
    }
}
