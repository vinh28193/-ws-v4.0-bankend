<?php


namespace api\modules\v1\controllers\service;

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
            'calculate' => ['POST'],
            'cancel' => ['GET']
        ];
    }

    public function actionCreate()
    {
        $post = $this->post;

        $transaction = Shipment::getDb()->beginTransaction();
        if (($id = ArrayHelper::remove($post, 'id', null)) === null) {
            return $this->response(false, "can not action when unknown Id");
        }
        if (($shipment = Shipment::findOne($id)) === null) {
            return $this->response(false, "can not action when not found shipment $id");
        }
        $parcels = ArrayHelper::remove($post, 'parcels', []);
        if (count($parcels) === 0) {
            return $this->response(false, "can not action when missing all parcel");
        }
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
        return $form->calculate();
    }


    public function actionCancel()
    {

    }
}