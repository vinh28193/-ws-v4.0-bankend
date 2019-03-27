<?php
namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use api\modules\v1\controllers\PurchaseController;
use common\models\db\ListAccountPurchase;
use Yii;

class PurchaseServiceController extends BaseApiController
{
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['list-account'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canView']
            ],
        ];
    }

    public function verbs()
    {
        return [
            'list-account' => ['GET'],
        ];
    }

    public function actionListAccount()
    {
        die("list-account");
//        $type = Yii::$app->request->get('type', 'all');
//        $account = ListAccountPurchase::find()->where(['active' => 1]);
//        if ($type !== 'all') {
//            $account->andWhere(['type' => strtolower($type)]);
//        }
//        $account = $account->asArray()->all();
//        return $this->response(true, "Success", $account);
    }


