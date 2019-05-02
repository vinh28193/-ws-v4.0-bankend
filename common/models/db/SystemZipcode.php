<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "system_zipcode".
 *
 * @property int $id ID
 * @property int $system_country_id  Weshop country id
 * @property int $system_state_province_id Weshop state province id
 * @property int $system_district_id Weshop district id
 * @property int $boxme_country_id Boxme country alias
 * @property int $boxme_state_province_id Boxme state province id
 * @property int $boxme_district_id Boxme district id
 * @property int $zip_code Zip code
 */
class SystemZipcode extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_zipcode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['system_country_id', 'system_state_province_id', 'system_district_id', 'boxme_country_id', 'boxme_state_province_id', 'boxme_district_id', 'zip_code'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'system_country_id' => 'System Country ID',
            'system_state_province_id' => 'System State Province ID',
            'system_district_id' => 'System District ID',
            'boxme_country_id' => 'Boxme Country ID',
            'boxme_state_province_id' => 'Boxme State Province ID',
            'boxme_district_id' => 'Boxme District ID',
            'zip_code' => 'Zip Code',
        ];
    }
}
