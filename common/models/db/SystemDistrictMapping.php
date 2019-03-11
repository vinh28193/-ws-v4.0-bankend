<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "system_district_mapping".
 *
 * @property int $id
 * @property int $district_id id system_district
 * @property int $province_id id system_province
 * @property int $box_me_district_id id district box me
 * @property int $box_me_province_id id province box me
 * @property string $district_name
 * @property string $province_name
 *
 * @property SystemDistrict $district
 * @property SystemStateProvince $province
 */
class SystemDistrictMapping extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_district_mapping';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['district_id', 'province_id', 'box_me_district_id', 'box_me_province_id'], 'integer'],
            [['district_name', 'province_name'], 'string', 'max' => 255],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemDistrict::className(), 'targetAttribute' => ['district_id' => 'id']],
            [['province_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemStateProvince::className(), 'targetAttribute' => ['province_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'district_id' => 'District ID',
            'province_id' => 'Province ID',
            'box_me_district_id' => 'Box Me District ID',
            'box_me_province_id' => 'Box Me Province ID',
            'district_name' => 'District Name',
            'province_name' => 'Province Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(SystemDistrict::className(), ['id' => 'district_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(SystemStateProvince::className(), ['id' => 'province_id']);
    }
}
