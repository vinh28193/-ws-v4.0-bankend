<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "system_country".
 *
 * @property int $id ID
 * @property string $name
 * @property string $country_code
 * @property string $country_code_2
 * @property string $language Nếu có nhiều , viết cách nhau bằng dấu phẩy
 * @property string $status
 * @property string $version version 4.0
 */
class SystemCountry extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'country_code', 'country_code_2', 'language', 'status', 'version'], 'string', 'max' => 255],
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
            'country_code' => 'Country Code',
            'country_code_2' => 'Country Code 2',
            'language' => 'Language',
            'status' => 'Status',
            'version' => 'Version',
        ];
    }
}
