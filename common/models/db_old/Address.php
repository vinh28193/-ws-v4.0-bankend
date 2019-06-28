<?php

namespace common\models\db_old;

use Yii;

/**
 * This is the model class for table "{{%address}}".
 *
 * @property int $id Mã địa chỉ tự sinh
 * @property string $FirstName Tên gọi
 * @property string $LastName Tên họ
 * @property string $Email Hòm thư điện tử liên lạc
 * @property string $Company Thông tin công ty
 * @property int $SystemCountryId Quốc gia
 * @property int $SystemStateProvinceId Tỉnh thành
 * @property int $SystemDistrictId Quận huyện
 * @property string $City Thành phố
 * @property string $Address1 Địa chỉ
 * @property string $Address2 Địa chỉ 2
 * @property string $ZipPostalCode Mã bưu điện
 * @property string $PhoneNumber Số điện thoại
 * @property string $FaxNumber Số fax
 * @property string $CreatedTime Ngày tạo địa chỉ
 * @property int $StoreId
 * @property int $Type
 * @property int $IsDefault
 * @property int $isDeleted
 * @property int $CustomerId
 *
 * @property SystemDistrictMapping $systemDistrictMapping
 * @property SystemStateProvince $systemStateProvince
 * @property Store $store
 * @property SystemDistrict $systemDistrict
 * @property SystemCountry $systemCountry
 * @property Customer $customer
 * @property Order[] $orders
 * @property Order[] $orders0
 * @property OrderAdditionFeeRequestPayment[] $orderAdditionFeeRequestPayments
 * @property OrderRefundRequestPayment[] $orderRefundRequestPayments
 * @property RequestPackages[] $requestPackages
 * @property RequestShipment[] $requestShipments
 * @property Shipment[] $shipments
 * @property Shipment[] $shipments0
 */
class Address extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%address}}';
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
            [['SystemCountryId', 'SystemStateProvinceId', 'SystemDistrictId', 'StoreId', 'Type', 'IsDefault', 'isDeleted', 'CustomerId'], 'integer'],
            [['CreatedTime'], 'safe'],
            [['FirstName', 'Company'], 'string', 'max' => 200],
            [['LastName', 'ZipPostalCode'], 'string', 'max' => 50],
            [['Email', 'City'], 'string', 'max' => 100],
            [['Address1', 'Address2'], 'string', 'max' => 255],
            [['PhoneNumber', 'FaxNumber'], 'string', 'max' => 20],
            [['SystemStateProvinceId'], 'exist', 'skipOnError' => true, 'targetClass' => SystemStateProvince::className(), 'targetAttribute' => ['SystemStateProvinceId' => 'id']],
            [['StoreId'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['StoreId' => 'id']],
            [['SystemDistrictId'], 'exist', 'skipOnError' => true, 'targetClass' => SystemDistrict::className(), 'targetAttribute' => ['SystemDistrictId' => 'id']],
            [['SystemCountryId'], 'exist', 'skipOnError' => true, 'targetClass' => SystemCountry::className(), 'targetAttribute' => ['SystemCountryId' => 'id']],
            [['CustomerId'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['CustomerId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'FirstName' => Yii::t('db', 'First Name'),
            'LastName' => Yii::t('db', 'Last Name'),
            'Email' => Yii::t('db', 'Email'),
            'Company' => Yii::t('db', 'Company'),
            'SystemCountryId' => Yii::t('db', 'System Country ID'),
            'SystemStateProvinceId' => Yii::t('db', 'System State Province ID'),
            'SystemDistrictId' => Yii::t('db', 'System District ID'),
            'City' => Yii::t('db', 'City'),
            'Address1' => Yii::t('db', 'Address1'),
            'Address2' => Yii::t('db', 'Address2'),
            'ZipPostalCode' => Yii::t('db', 'Zip Postal Code'),
            'PhoneNumber' => Yii::t('db', 'Phone Number'),
            'FaxNumber' => Yii::t('db', 'Fax Number'),
            'CreatedTime' => Yii::t('db', 'Created Time'),
            'StoreId' => Yii::t('db', 'Store ID'),
            'Type' => Yii::t('db', 'Type'),
            'IsDefault' => Yii::t('db', 'Is Default'),
            'isDeleted' => Yii::t('db', 'Is Deleted'),
            'CustomerId' => Yii::t('db', 'Customer ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSystemStateProvince()
    {
        return $this->hasOne(SystemStateProvince::className(), ['id' => 'SystemStateProvinceId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'StoreId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSystemDistrict()
    {
        return $this->hasOne(SystemDistrict::className(), ['id' => 'SystemDistrictId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSystemCountry()
    {
        return $this->hasOne(SystemCountry::className(), ['id' => 'SystemCountryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'CustomerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['BillingAddressId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders0()
    {
        return $this->hasMany(Order::className(), ['ShippingAddressId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderAdditionFeeRequestPayments()
    {
        return $this->hasMany(OrderAdditionFeeRequestPayment::className(), ['BillingAddressId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderRefundRequestPayments()
    {
        return $this->hasMany(OrderRefundRequestPayment::className(), ['BillingAddressId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestPackages()
    {
        return $this->hasMany(RequestPackages::className(), ['shipping_address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestShipments()
    {
        return $this->hasMany(RequestShipment::className(), ['ShippingAddressId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipments()
    {
        return $this->hasMany(Shipment::className(), ['shippingAddressId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipments0()
    {
        return $this->hasMany(Shipment::className(), ['billingAddressId' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSystemDistrictMapping()
    {
        return $this->hasOne(SystemDistrictMapping::className(), ['system_province_id' => 'SystemStateProvinceId', 'system_district_id' => 'SystemDistrictId']);
    }
}
