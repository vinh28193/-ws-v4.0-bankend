<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "store".
 *
 * @property int $id ID
 * @property int $country_id
 * @property string $locale
 * @property string $name
 * @property string $country_name
 * @property string $address
 * @property string $url
 * @property string $currency
 * @property int $currency_id
 * @property int $status
 * @property int $env PROD or UAT or BETA ...
 * @property string $version version 4.0
 */
class Store extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'currency_id', 'status', 'env'], 'integer'],
            [['address'], 'string'],
            [['locale', 'name', 'country_name', 'url', 'currency', 'version'], 'string', 'max' => 255],
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
            'locale' => 'Locale',
            'name' => 'Name',
            'country_name' => 'Country Name',
            'address' => 'Address',
            'url' => 'Url',
            'currency' => 'Currency',
            'currency_id' => 'Currency ID',
            'status' => 'Status',
            'env' => 'Env',
            'version' => 'Version',
        ];
    }
}
