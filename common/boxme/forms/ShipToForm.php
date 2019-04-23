<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 14:41
 */

namespace common\boxme\forms;

use yii\base\Model;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\boxme\Location;
use common\models\db\SystemDistrictMapping;

class ShipToForm extends Model
{
    public $contact_name;
    public $company_name;
    public $address;
    public $address2;
    public $phone;
    public $phone2;
    public $country;
    public $province;
    public $district;
    public $zipcode;

    public function attributes()
    {
        return [
            'contact_name', 'company_name', 'address', 'address2', 'phone', 'phone2', 'country', 'province', 'district', 'zipcode', 'area','pickup_id',
        ];
    }

    public function rules()
    {
        $mapping = $mapping = $this->getMapping();
        return [
            [['province', 'district', 'pickup_id'], 'integer'],
            [['contact_name', 'company_name', 'address', 'address2', 'phone', 'phone2', 'country', 'zipcode'], 'string'],
            [['contact_name', 'address', 'phone', 'country', 'province', 'district'], 'required'],
            ['zipcode', 'required', 'when' => function ($self) {
                return $self->country === Location::COUNTRY_ID;
            }],
            ['district', function ($attribute, $param) use ($mapping) {
                if (!$this->hasErrors()) {
                    if ($mapping === null) {
                        $this->addError($attribute, 'Not Found mapping boxme_district_id');
                    }else{
                        $this->$attribute = $mapping->boxme_district_id; // Trả lại dữ liệu mới cho $attribute (district)
                    }

                }
            }],
            ['province', function ($attribute, $param) use ($mapping) {
                if (!$this->hasErrors()) {
                    if ($mapping === null) {
                        $this->addError($attribute, 'Not Found mapping boxme_city_id');
                    }else{
                        $this->$attribute = $mapping->boxme_city_id; // Trả lại dữ liệu mới cho $attribute (province)
                    }

                }
            }],
            ['zipcode', function ($attribute, $param) {
                if (!$this->hasErrors()) {
                    $zipCodeAlive = $this->getZipCode(); // Chắc chắn validate của district và province đã được chạy trước đó,.
                    if (($value = $this->$attribute) === null || $value === '' || !ArrayHelper::isIn($value, $zipCodeAlive)) {
                        $this->addError($attribute, 'invalid Zip code');
                    }
                }
            }, 'when' => function ($self) {
                return $self->country === Location::COUNTRY_ID;
            }],
            [['company_name','address2','phone2'],'default','value' => ' ']
        ];
    }

    public function getMapping()
    {
        return SystemDistrictMapping::find()->where([
            'AND',
            ['system_district_id' => $this->district],
            ['system_province_id' => $this->province],
            ['IS NOT', 'boxme_district_id', new Expression('NULL')],
            ['IS NOT', 'boxme_city_id', new Expression('NULL')]
        ])->orderBy(['id' => SORT_DESC])->one();
    }

    public function getZipCode()
    {
        return [];
//        $zipCodeAlive = SystemZipcode::getAvailableZipCode($this->province, $this->district);
//        return ArrayHelper::getColumn($zipCodeAlive, 'zip_code', false);
    }
}