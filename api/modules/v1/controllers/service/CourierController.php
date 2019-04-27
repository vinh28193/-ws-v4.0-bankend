<?php


namespace api\modules\v1\controllers\service;

use common\boxme\BoxmeClientCollection;
use common\boxme\Location;
use common\models\draft\DraftDataTracking;
use common\models\Package;
use common\models\PackageItem;
use common\models\Product;
use common\models\Shipment as ModelShipment;
use Yii;
use Exception;
use common\models\Shipment;
use common\boxme\forms\CalculateForm;
use common\boxme\forms\CreateOrderForm;
use api\controllers\BaseApiController;
use yii\helpers\ArrayHelper;

class CourierController extends BaseApiController
{

    protected function rules()
    {
        return [
            [
                'allow' => true,
            ]
        ];
    }

    protected function verbs()
    {
        return [
            'create' => ['POST'],
            'bulk' => ['POST'],
            'calculate' => ['POST'],
            'cancel' => ['POST']
        ];
    }

    public function actionCreate()
    {
        $post = $this->post;


        if (($id = ArrayHelper::remove($post, 'id', null)) === null) {
            return $this->response(false, "can not action when unknown Id");
        }
        if (($shipment = Shipment::findOne($id)) === null) {
            return $this->response(false, "can not action when not found shipment $id");
        }
        $parcels = ArrayHelper::remove($post, 'parcels', []);
//        if (count($parcels) === 0) {
//            return $this->response(false, "can not action when missing all parcel");
//        }
        $transaction = Shipment::getDb()->beginTransaction();
        foreach (['total_price', 'total_cod', 'payment_method', 'description', 'note_for_courier'] as $remove) {
            if (isset($post[$remove])) {
                unset($post[$remove]);
            }
        }
        try {
            if (!$shipment->load($post, '')) {
                $transaction->rollBack();
                return $this->response(false, "shipment can not resolve parameter");
            }
            $shipment->save(false);
//            $shipment->reCalculateTotal();
            $transaction->commit();
            $shipment->refresh();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            Yii::error($exception, __METHOD__);
            return $this->response(false, $exception->getMessage());
        }
        $createForm = new CreateOrderForm();
        /* @var $model ModelShipment */
        list($isSuccess, $mess) = $createForm->createByShipment($shipment);
        return $this->response($isSuccess, $mess);


    }

    public function actionCreateBulk()
    {
        $post = $this->post;
        $ids = ArrayHelper::getValue($post, 'ids', []);
        if (count($ids) === 0) {
            return $this->response(false, "nothing to create");
        }
        $rules = ArrayHelper::getValue($post, 'rules', []);
        $createForm = new CreateOrderForm();
        $createForm->ids = $ids;
        $createForm->rules = $rules;
        if (($rs = $createForm->create()) === false) {
            return $this->response(false, $createForm->getFirstErrors());
        }
        return $this->response(true, $rs);
    }

    public function actionCalculate()
    {
        $bodyParams = Yii::$app->request->bodyParams;
        $form = new CalculateForm();
        if (!$form->load($bodyParams, '')) {
            return [
                'error' => true,
                'error_code' => 'Error Validate',
                'messages' => $form->getFirstErrors(),
                'data' => []
            ];
        }
        if ($form->toCountry != 1 && !$form->toZipCode) {
            return [
                'error' => true,
                'error_code' => 'Error Validate',
                'messages' => "Zipcode Indo cannot null!",
                'data' => []
            ];
        }
        return $form->calculate();
    }


    public function actionCancel()
    {
        $ids = ArrayHelper::getValue($this->post, 'ids', []);
        if (count($ids) === 0) {
            $this->response(true, "no thing to cancel");
        }
        $shipments = Shipment::find()->with('warehouseSend')->where(['AND', ['IN', 'id', $ids], ['active' => 1]])->all();
        if (count($shipments) === 0) {
            $this->response(true, "no thing to cancel");
        }
        $error = [];
        $success = 0;
        foreach ($shipments as $shipment) {
            /** @var $shipment Shipment */
            if ($shipment->shipment_status !== Shipment::STATUS_CREATED || $shipment->shipment_code === null) {
                $error[] = "not found BM code or invalid status";
                continue;
            }
            $collection = new BoxmeClientCollection();
            $client = $collection->getClient($shipment->warehouseSend->country_id === 2 ? Location::COUNTRY_ID : Location::COUNTRY_VN);
            $res = $client->cancelOrder($shipment->shipment_code);
            if ($res['error'] === true) {
                $error[] = $res['messages'];
                continue;
            }
            $shipment->shipment_code = null;
            $shipment->shipment_status = Shipment::STATUS_NEW;
            $shipment->courier_code = null;
            $shipment->courier_logo = null;
            $shipment->total_shipping_fee = null;
            $shipment->save(false);
            $success++;
        }
        $mess = "cancel success $success shipment";
        if (count($error) > 0) {
            $error = implode(',', $error);
            $mess .= ", can not cancel $error";
        }

        $this->response(true, $mess);
    }
}