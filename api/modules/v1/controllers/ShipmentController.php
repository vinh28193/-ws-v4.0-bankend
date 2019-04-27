<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-15
 * Time: 20:41
 */

namespace api\modules\v1\controllers;

use common\helpers\WeshopHelper;
use common\models\db\DeliveryNote;
use Yii;
use Exception;
use yii\helpers\ArrayHelper;
use api\controllers\BaseApiController;
use common\data\ActiveDataProvider;
use common\models\PackageItem;
use common\models\Shipment;

/**
 * Class ShipmentController
 * @package api\modules\v1\controllers
 *
 * shipment router
 * GET => index
 * POST => create
 * POST id => calculate
 * GET id => cancel
 */
class ShipmentController extends BaseApiController
{

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'update' => ['PATCH', 'PUT'],
            'merge' => ['POST'],
            'remove-item' => ['GET']
        ];
    }

    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => $this->getAllRoles(true, ['user', 'sale', 'marketing'])
            ],
        ];
    }

    /**
     * list all shipment
     * @return array
     */
    public function actionIndex()
    {
        $params = $this->get;
        $query = Shipment::find();
        $query->filterRelation();
        $query->filter($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeParam' => 'perPage',
                'params' => $params,
            ],
            'sort' => [
                'params' => $params,
            ],
        ]);



        return $this->response(true, "get shipment success", $dataProvider);
    }

    public function actionUpdate($id)
    {
        if (($shipment = Shipment::findOne($id)) === null) {
            return $this->response(false, "can not action when not found shipment $id");
        }
        $parcels = ArrayHelper::remove($post, 'parcels', []);
        if (count($parcels) === 0) {
            return $this->response(false, "can not action when missing all parcel");
        }
        $transaction = Shipment::getDb()->beginTransaction();
        try {
            if (!$shipment->load($post, '')) {
                $transaction->rollBack();
                return $this->response(false, "shipment can not resolve parameter");
            }
            $shipment->save(false);
            foreach ($parcels as $parcel) {
                if (($pId = ArrayHelper::remove($parcel, 'id', null)) === null) {
                    $transaction->rollBack();
                    return $this->response(false, "failed a parcel not found parameter ID ");
                }
                /** @var $packageItem PackageItem */
                if (($packageItem = PackageItem::find()->where(['and', ['id' => $pId], ['shipment_id' => $shipment->id]])->one()) === null) {
                    $transaction->rollBack();
                    return $this->response(false, "failed not found package item $pId in shipment {$shipment->id}");
                }
//                if(($productId = ArrayHelper::remove($parcel,'product_id')) === null){
//                    $transaction->rollBack();
//                    return $this->response(false, "failed a parcel not found parameter Product ID");
//                }
//                if(($product = Product::findOne($productId)) === null){
//                    $transaction->rollBack();
//                    return $this->response(false, "failed not found product $productId for package item {$packageItem->id}");
//                }
//                if($name = ArrayHelper::remove($parcel,'name') !== null){
//
//                }
                unset($parcel['product_id']);
                unset($parcel['image']);
                unset($parcel['name']);
                $packageItem->load($parcel, '');
                $packageItem->save(false);
            }
            $shipment->reCalculateTotal();
            $transaction->commit();
            return $this->response(true, "shipment $id is up to date");
        } catch (Exception $exception) {
            $transaction->rollBack();
            Yii::error($exception, __METHOD__);
            return $this->response(false, $exception->getMessage());
        }
    }

    public function actionMerge()
    {
        $post = $this->post;
        $ids = ArrayHelper::getValue($this->post, 'ids', []);
        if (count($ids) === 0) {
            return $this->response(false, "nothing to update");
        }
        $shipments = Shipment::find()->where(['AND', ['IN', 'id', $ids], ['active' => 1]])->all();
        if (empty($shipments) || count($shipments) < 1) {
            return $this->response(false, "nothing to update");
        }
        if (WeshopHelper::isDiffArrayValue($shipments, 'customer_id') || WeshopHelper::isDiffArrayValue($shipments, 'warehouse_send_id')) {
            return $this->response(false, "can not merge form diff customer or warehouse");
        }
        $transaction = Shipment::getDb()->beginTransaction();
        try {
            /** @var $firstShipment Shipment */
            $firstShipment = array_shift($shipments);
            $mess = "Moved {moved} in {package} form shipment {form} to {$firstShipment->id}";
            $moved = 0;
            $form = [];
            $packageCodes = [];
            foreach ($shipments as $shipment) {
                /** @var $shipment Shipment */
                foreach ($shipment->packages as $package){
                    /** @var $package DeliveryNote */
                    $package->shipment_id = $firstShipment->id;
                    $packageCodes[] = $package->package_code;
                    $package->save(false);
                }
                foreach ($shipment->packageItems as $packageItem) {
                    $packageItem->shipment_id = $firstShipment->id;
                    $packageItem->save(false);
                    $moved++;
                }
                // todo remove shipment
                $shipment->active = 0;
                $shipment->save(false);
                $form[] = $shipment->id;

            }
            $mess = str_replace(['{moved}', '{package}' ,'{form}'], [$moved, implode(',', $packageCodes), implode(',', $form)], $mess);
            $firstShipment->reCalculateTotal();
            $transaction->commit();
            return $this->response(true, $mess);
        } catch (Exception $exception) {
            Yii::error($exception,__METHOD__);
            $transaction->rollBack();
            return $this->response(false,$exception->getMessage());
        }
    }

    public function actionRemoveItem($id)
    {

    }
}