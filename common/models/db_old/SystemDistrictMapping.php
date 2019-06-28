<?php

namespace common\models\db_old;

use common\models\db\SystemCountry;
use common\models\db\SystemDistrict;
use Yii;

/**
 * This is the model class for table "{{%system_district_mapping}}".
 *
 * @property int $id
 * @property int $system_district_id
 * @property int $system_province_id
 * @property int $shipjung_district_id
 * @property string $shipjung_district_name
 * @property string $shipjung_district_name_local
 * @property string $shipjung_zip_code
 * @property int $shipjung_city_id
 * @property int $boxme_district_id
 * @property int $boxme_city_id
 * @property int $area 1 or 0, 1 nếu là nội thành hoặc thành phố của tỉnh,0 tuyến huyện xã
 *
 * @property SystemDistrict $systemDistrict
 */
class SystemDistrictMapping extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%system_district_mapping}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_old');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['system_district_id', 'system_province_id', 'shipjung_district_id', 'shipjung_city_id', 'boxme_district_id', 'boxme_city_id', 'area'], 'integer'],
            [['shipjung_district_name', 'shipjung_district_name_local'], 'string', 'max' => 200],
            [['shipjung_zip_code'], 'string', 'max' => 20],
            [['system_district_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemDistrict::className(), 'targetAttribute' => ['system_district_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'system_district_id' => Yii::t('db', 'System District ID'),
            'system_province_id' => Yii::t('db', 'System Province ID'),
            'shipjung_district_id' => Yii::t('db', 'Shipjung District ID'),
            'shipjung_district_name' => Yii::t('db', 'Shipjung District Name'),
            'shipjung_district_name_local' => Yii::t('db', 'Shipjung District Name Local'),
            'shipjung_zip_code' => Yii::t('db', 'Shipjung Zip Code'),
            'shipjung_city_id' => Yii::t('db', 'Shipjung City ID'),
            'boxme_district_id' => Yii::t('db', 'Boxme District ID'),
            'boxme_city_id' => Yii::t('db', 'Boxme City ID'),
            'area' => Yii::t('db', 'Area'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSystemDistrict()
    {
        return $this->hasOne(SystemDistrict::className(), ['id' => 'boxme_district_id']);
    }
}
