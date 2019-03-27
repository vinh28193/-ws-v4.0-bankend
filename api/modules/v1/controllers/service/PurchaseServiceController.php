<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 27/03/2019
 * Time: 3:37 CH
 */

namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use api\modules\v1\controllers\PurchaseController;
use common\models\db\ListAccountBuyer;
use Yii;

class PurchaseServiceController extends BaseApiController
{
    public function verbs()
    {
        return [
            'get-list-account' => ['GET'],
            'get-list-card-payment' => ['POST']
        ];
    }

    public function actionGetListAccount()
    {
        die("ewewqeqwewqeqw");
        $type = Yii::$app->request->get('type', 'all');
        $account = ListAccountBuyer::find()->where(['active' => 1]);
        if ($type !== 'all') {
            $account->andWhere(['type' => strtolower($type)]);
        }
        $account = $account->asArray()->all();
        return $this->response(true, "Success", $account);
    }

}
