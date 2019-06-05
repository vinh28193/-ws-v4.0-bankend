<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%manifest}}".
 *
 * @property int $id
 * @property string $manifest_code Mã kiện về (từ us/jp ..)
 * @property int $send_warehouse_id Kho gửi đi
 * @property int $receive_warehouse_id Kho nhận
 * @property string $us_stock_out_time ngày xuất kho mỹ
 * @property string $local_stock_in_time ngày nhậo kho việt nam
 * @property string $local_stock_out_time ngày xuất kho
 * @property int $store_id
 * @property int $created_by người tạo
 * @property int $updated_by
 * @property int $created_at ngày tạo
 * @property int $updated_at
 * @property int $active
 * @property string $version version 4.0
 * @property string $status
 */
class Manifest extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%manifest}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['manifest_code'], 'required'],
            [['send_warehouse_id', 'receive_warehouse_id', 'store_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'active'], 'integer'],
            [['us_stock_out_time', 'local_stock_in_time', 'local_stock_out_time'], 'safe'],
            [['manifest_code'], 'string', 'max' => 32],
            [['version', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'manifest_code' => Yii::t('db', 'Manifest Code'),
            'send_warehouse_id' => Yii::t('db', 'Send Warehouse ID'),
            'receive_warehouse_id' => Yii::t('db', 'Receive Warehouse ID'),
            'us_stock_out_time' => Yii::t('db', 'Us Stock Out Time'),
            'local_stock_in_time' => Yii::t('db', 'Local Stock In Time'),
            'local_stock_out_time' => Yii::t('db', 'Local Stock Out Time'),
            'store_id' => Yii::t('db', 'Store ID'),
            'created_by' => Yii::t('db', 'Created By'),
            'updated_by' => Yii::t('db', 'Updated By'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'active' => Yii::t('db', 'Active'),
            'version' => Yii::t('db', 'Version'),
            'status' => Yii::t('db', 'Status'),
        ];
    }
}
