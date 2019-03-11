<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "manifest".
 *
 * @property int $id
 * @property string $manifest_code Mã lô
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
 *
 * @property User $createdBy
 * @property Warehouse $receiveWarehouse
 * @property Store $store
 * @property User $updatedBy
 * @property Warehouse $sendWarehouse
 */
class Manifest extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manifest';
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
            [['manifest_code'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['receive_warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::className(), 'targetAttribute' => ['receive_warehouse_id' => 'id']],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['send_warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::className(), 'targetAttribute' => ['send_warehouse_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manifest_code' => 'Manifest Code',
            'send_warehouse_id' => 'Send Warehouse ID',
            'receive_warehouse_id' => 'Receive Warehouse ID',
            'us_stock_out_time' => 'Us Stock Out Time',
            'local_stock_in_time' => 'Local Stock In Time',
            'local_stock_out_time' => 'Local Stock Out Time',
            'store_id' => 'Store ID',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'active' => 'Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiveWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'receive_warehouse_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSendWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'send_warehouse_id']);
    }
}
