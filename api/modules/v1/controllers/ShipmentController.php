<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-15
 * Time: 20:41
 */

namespace api\modules\v1\controllers;

use common\boxme\forms\CreateOrderForm;
use common\helpers\WeshopHelper;
use common\models\DeliveryNote;
use common\models\db\Package;
use common\models\Shipment as ModelShipment;
use Yii;
use Exception;
use yii\helpers\ArrayHelper;
use api\controllers\BaseApiController;
use common\data\ActiveDataProvider;
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
                'pageSizeLimit' => [1,500],
                'pageSizeParam' => 'prep',
                'pageParam' => 'p',
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
//            foreach ($parcels as $parcel) {
//                if (($pId = ArrayHelper::remove($parcel, 'id', null)) === null) {
//                    $transaction->rollBack();
//                    return $this->response(false, "failed a parcel not found parameter ID ");
//                }
//                /** @var $packageItem PackageItem */
//                if (($packageItem = PackageItem::find()->where(['and', ['id' => $pId], ['shipment_id' => $shipment->id]])->one()) === null) {
//                    $transaction->rollBack();
//                    return $this->response(false, "failed not found package item $pId in shipment {$shipment->id}");
//                }
////                if(($productId = ArrayHelper::remove($parcel,'product_id')) === null){
////                    $transaction->rollBack();
////                    return $this->response(false, "failed a parcel not found parameter Product ID");
////                }
////                if(($product = Product::findOne($productId)) === null){
////                    $transaction->rollBack();
////                    return $this->response(false, "failed not found product $productId for package item {$packageItem->id}");
////                }
////                if($name = ArrayHelper::remove($parcel,'name') !== null){
////
////                }
//                unset($parcel['product_id']);
//                unset($parcel['image']);
//                unset($parcel['name']);
//                $packageItem->load($parcel, '');
//                $packageItem->save(false);
//            }
//            $shipment->reCalculateTotal();
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
                foreach ($shipment->deliveryNotes as $deliveryNotes) {
                    /** @var $deliveryNotes \common\models\DeliveryNote */
                    $deliveryNotes->shipment_id = $firstShipment->id;
                    $deliveryNotes->save(false);
                }
                foreach ($shipment->packages as $package){
                    /** @var $package Package */
                    $package->shipment_id = $firstShipment->id;
                    $packageCodes[] = $package->delivery_note_code;
                    $package->save(false);
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
    public function actionCreate(){
        $post = $this->post;

        if (($ids = ArrayHelper::remove($post, 'listIds', null)) === null) {
            return $this->response(false, "can not action when unknown Id");
        }

        if (($courier = ArrayHelper::remove($post, 'courier', null)) === null) {
            return $this->response(false, "Please select courier!");
        }
        /** @var DeliveryNote[] $listDelivery */
        $listDelivery = DeliveryNote::find()->with(['packages'])->where(['id' => $ids])->all();
        $warehouse_tags = [];
        if(count($listDelivery) !== count($ids)){
            return $this->response(false, "Some delivery error!");
        }
        foreach ($listDelivery as $item){
            if($item->shipment_id){
                return $this->response(false, "Have delivery was created shipment!");
            }
            foreach ($item->packages as $package){
                if(!in_array($package->warehouse_tag_boxme,$warehouse_tags)){
                    $warehouse_tags[] =  $package->warehouse_tag_boxme;
                }
            }
        }
        $shipment = new Shipment();
        $shipment->customer_id = ArrayHelper::remove($post, 'customer_id', null);
        $shipment->total_weight = ArrayHelper::remove($post, 'weight', 0);
        $shipment->total_price = ArrayHelper::remove($post, 'price', 0);
        $shipment->total_cod = ArrayHelper::remove($post, 'cod', 0);
        $shipment->warehouse_send_id = ArrayHelper::remove($post, 'warehouse_pickup', null);
        $shipment->receiver_name = ArrayHelper::remove($post, 'receiver_name', null);
        $shipment->receiver_email = ArrayHelper::remove($post, 'receiver_email', null);
        $shipment->receiver_phone = ArrayHelper::remove($post, 'receiver_phone', null);
        $shipment->receiver_district_name = ArrayHelper::remove($post, 'receiver_district_name', null);
        $shipment->receiver_district_id = ArrayHelper::remove($post, 'receiver_district_id', null);
        $shipment->receiver_province_id = ArrayHelper::remove($post, 'receiver_province_id', null);
        $shipment->receiver_province_name = ArrayHelper::remove($post, 'receiver_province_name', null);
        $shipment->receiver_country_name = ArrayHelper::remove($post, 'receiver_country_name', null);
        $shipment->receiver_country_id = ArrayHelper::remove($post, 'receiver_country_id', null);
        $shipment->receiver_address = ArrayHelper::remove($post, 'receiver_address', null);
        $shipment->receiver_post_code = ArrayHelper::remove($post, 'receiver_post_code', null);
        $shipment->is_insurance = ArrayHelper::remove($post, 'insurance', null);
        $shipment->courier_code = ArrayHelper::remove($courier, 'service_code', null);
        $shipment->courier_logo = ArrayHelper::remove($courier, 'courier_logo', null);
        $shipment->courier_estimate_time = ArrayHelper::remove($courier, 'min_delivery_time', '') .' days - '.ArrayHelper::remove($courier, 'max_delivery_time', '').' days';
        $shipment->total_shipping_fee = ArrayHelper::remove($courier, 'shipping_fee', 0);
        $transaction = Shipment::getDb()->beginTransaction();
        foreach (['total_price', 'total_cod', 'payment_method', 'description', 'note_for_courier'] as $remove) {
            if (isset($post[$remove])) {
                unset($post[$remove]);
            }
        }
        try {
            $shipment->save(false);
            DeliveryNote::updateAll(['shipment_id' => $shipment->id, 'current_status' => Shipment::STATUS_CREATED],['id' => $ids]);
            Package::updateAll(['shipment_id' => $shipment->id, 'current_status' => Shipment::STATUS_CREATED],['delivery_note_id' => $ids]);
            $shipment = Shipment::findOne($shipment->id);
            $createForm = new CreateOrderForm();
            /* @var $model ModelShipment */
            list($isSuccess, $mess) = $createForm->createByShipment($shipment);
//            $shipment->reCalculateTotal();
            if($isSuccess){
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
            return $this->response($isSuccess, $mess);
        } catch (\Exception $exception) {
            $transaction->rollBack();
            Yii::error($exception, __METHOD__);
            return $this->response(false, $exception->getMessage());
        }
    }
}