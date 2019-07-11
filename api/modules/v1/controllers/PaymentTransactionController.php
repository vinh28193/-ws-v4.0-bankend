<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 7/2/2019
 * Time: 7:23 PM
 */

namespace api\modules\v1\controllers;
use api\controllers\BaseApiController;
use common\models\db\PaymentTransaction;
use Yii;


class PaymentTransactionController extends BaseApiController
{
    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET']
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
                'actions' => ['index', 'view', 'update'],
                'roles' => ['master_operation', 'tester', 'master_sale', 'sale', 'master_accountant', 'accountant', 'operation']
            ],
        ];
    }
    public function actionIndex() {
        $model = PaymentTransaction::find()->asArray()->all();
        return $this->response(true, 'success', $model);
    }
    public function actionUpdate($code) {
        /** @var PaymentTransaction[] $models */
        $post = Yii::$app->request->post();
        $model = PaymentTransaction::find()->where(['order_code' => $code])->one();
        $model->transaction_status = 'SUCCESS';
        if (isset($post['note'])) {
            $model->note = $post['note'];
            $model->updated_at = Yii::$app->getFormatter()->asTimestamp('now');
        }
        if (isset($post['link_image'])) {
            $model->link_image = $post['link_image'];
        }
        $model->save(false);
        return $this->response(true, 'success');
    }
    public function actionView($code) {
        $model = PaymentTransaction::find()->select([
            'id',
            'order_code',
            'transaction_code',
            'transaction_amount_local',
            'transaction_type',
            'transaction_status',
            'transaction_description',
            'note',
            'link_image',
            'third_party_transaction_link',
            'payment_bank_code',
            'created_at',
            'updated_at',
        ])->where(['order_code' => $code])->asArray()->one();
        return $this->response(true, 'success', $model);
    }
}