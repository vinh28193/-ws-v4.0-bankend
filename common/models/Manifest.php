<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 06/03/2019
 * Time: 17:02
 */

namespace common\models;

use common\models\db\Manifest as DbManifest;
use common\models\db\Store;
use common\models\db\User;
use common\models\db\Warehouse;
use common\models\draft\DraftMissingTracking;
use common\models\Package;
use common\models\draft\DraftWastingTracking;

/**
 * Class Manifest
 * @package common\models
 * @property DeliveryNote[] $packages
 * @property DraftWastingTracking[] $draftWastingTrackings
 * @property DraftMissingTracking[] $draftMissingTrackings
 * @property Package[] $draftPackageItems
 * @property Package[] $unknownTrackings
 * @property User $createdBy
 * @property Warehouse $receiveWarehouse
 * @property Store $store
 * @property User $updatedBy
 * @property Warehouse $sendWarehouse
 */
class Manifest extends DbManifest
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    const STATUS_NEW = 'NEW';
    const STATUS_EMPTY = 'EMPTY';
    const STATUS_MERGING = 'MERGING';
    const STATUS_MERGE_DONE = 'MERGE_DONE';
    const STATUS_TYPE_GETTING = 'TYPE_GETTING';
    const STATUS_TYPE_GET_DONE = 'TYPE_GET_DONE';
    const STATUS_INSPECTING = 'INSPECTING';
    const STATUS_INSPECT_DONE = 'WAITING_INSPECT';
    const STATUS_PACKING_CREATING = 'PACKING_CREATING';
    const STATUS_PACKING_CREATED = 'PACKING_CREATED';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $paren = parent::rules();
        $child = [
            ['active', 'default', 'value' => self::STATUS_ACTIVE],
            ['active', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
        return array_merge($paren, $child);
    }

    public static function createSafe($code, $receiveWarehouse, $store)
    {
        $build = [
            'manifest_code' => $code,
            'receive_warehouse_id' => $receiveWarehouse,
            'store_id' => $store
        ];
        $query = self::find()->andWhere($build)->andWhere(['active' => 1]);
        if (($manifest = $query->one()) === null) {
            $manifest = new Manifest($build);
            $manifest->status = self::STATUS_NEW;
            $manifest->save(false);
        }
        return $manifest;

    }

    public function getDraftPackageItems(){
        return $this->hasMany(Package::className(),['manifest_id' => 'id'])->where(['and',['is not','product_id',null],['<>','product_id',''],['<>','status',Package::STATUS_SPLITED]]);
    }

    public function getUnknownTrackings(){
        return $this->hasMany(Package::className(),['manifest_id' => 'id'])->where(['or',['product_id' => null],['product_id' => '']])->andWhere(['<>','status',Package::STATUS_SPLITED]);
    }

    public function getDraftMissingTrackings(){
        return $this->hasMany(DraftMissingTracking::className(),['manifest_id' => 'id']);
    }
    public function getDraftWastingTrackings(){
        return $this->hasMany(DraftWastingTracking::className(),['manifest_id' => 'id']);
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

    /**
     * {@inheritdoc}
     * @return \common\models\queries\ManifestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\ManifestQuery(get_called_class());
    }
}