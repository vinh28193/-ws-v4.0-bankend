<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 06/03/2019
 * Time: 17:02
 */

namespace common\models;

use common\models\db\Manifest as DbManifest;
use common\models\draft\DraftMissingTracking;
use common\models\draft\DraftPackageItem;
use common\models\draft\DraftWastingTracking;

/**
 * Class Manifest
 * @package common\models
 * @property Package[] $packages
 * @property DraftWastingTracking[] $draftWastingTrackings
 * @property DraftMissingTracking[] $draftMissingTrackings
 * @property DraftPackageItem[] $draftPackageItems
 * @property DraftPackageItem[] $unknownTrackings
 */
class Manifest extends DbManifest
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

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
            $manifest->save(false);
        }
        return $manifest;

    }

    public function getDraftPackageItems(){
        return $this->hasMany(DraftPackageItem::className(),['manifest_id' => 'id'])->where(['and',['is not','product_id',null],['<>','product_id','']]);
    }

    public function getUnknownTrackings(){
        return $this->hasMany(DraftPackageItem::className(),['manifest_id' => 'id'])->where(['or',['product_id' => null],['product_id' => '']]);
    }

    public function getDraftMissingTrackings(){
        return $this->hasMany(DraftMissingTracking::className(),['manifest_id' => 'id']);
    }
    public function getDraftWastingTrackings(){
        return $this->hasMany(DraftWastingTracking::className(),['manifest_id' => 'id']);
    }
}