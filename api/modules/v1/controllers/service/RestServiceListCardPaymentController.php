<?php

namespace api\modules\v1\controllers\service;

use api\controllers\BaseApiController;
use Yii;
use common\models\db\PurchasePaymentCard;

class RestServiceListCardPaymentController extends BaseApiController
{
    /** Role :
        case 'cms':
        case 'warehouse':
        case 'operation':
        case 'sale':
        case 'master_sale':
        case 'master_operation':
        case 'superAdmin' :
    **/
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['list-card-payment'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canView']
            ],
        ];
    }

    public function verbs()
    {
        return [
            'list-card-payment' => ['POST']
        ];
    }

    public function actionListCardPayment()
    {
        die("list-card-payment");
//        $storeId = Yii::$app->request->get('store', 1);
//        $storeId = $storeId ? $storeId : 1;
//        // $list_data = PurchasePaymentCard::find()->where(['store_id' => $storeId , 'status' => 1])->asArray()->all();
//        $list_data = PurchasePaymentCard::find()->where(['status' => 1])->asArray()->all();
//        return $this->response(true, "Success", $list_data);

    }






}
