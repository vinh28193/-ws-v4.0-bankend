<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "warehouse".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $district_id
 * @property int $province_id
 * @property int $country_id
 * @property int $store_id
 * @property string $address
 * @property int $type 1: Full Operation Warehouse , 2 : Transit Warehouse 
 * @property string $warehouse_group 1: Nhóm Transit , 2 : nhóm lưu trữ, 3 : nhóm note purchase. Các nhóm sẽ đc khai báo const 
 * @property string $post_code
 * @property string $telephone
 * @property string $email
 * @property string $contact_person
 * @property int $ref_warehouse_id
 * @property string $created_at thời gian tạo
 * @property string $updated_at thời gian cập nhật
 * @property string $version version 4.0
 */
class Warehouse extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'warehouse';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['district_id', 'province_id', 'country_id', 'store_id', 'type', 'ref_warehouse_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'description', 'address', 'warehouse_group', 'post_code', 'telephone', 'email', 'contact_person', 'version'], 'string', 'max' => 255],
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
            'description' => 'Description',
            'district_id' => 'District ID',
            'province_id' => 'Province ID',
            'country_id' => 'Country ID',
            'store_id' => 'Store ID',
            'address' => 'Address',
            'type' => 'Type',
            'warehouse_group' => 'Warehouse Group',
            'post_code' => 'Post Code',
            'telephone' => 'Telephone',
            'email' => 'Email',
            'contact_person' => 'Contact Person',
            'ref_warehouse_id' => 'Ref Warehouse ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'version' => 'Version',
        ];
    }
}
