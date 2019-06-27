<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 6/27/2019
 * Time: 8:16 PM
 */

namespace api\modules\v1\controllers;


use common\fixtures\SystemExchangeRateFixture;
use common\models\db\SystemExchangeRate;
use api\controllers\BaseApiController;
use Yii;

class ExchangeRateController extends BaseApiController
{
    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index','view','delete','update','create'],
                'roles' => ['superAdmin']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        return [
            'index' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionIndex() {
        $model = SystemExchangeRate::find()->asArray()->all();
        return $this->response(true, 'success', $model);
    }

    public function actionUpdate($id) {
        $post = Yii::$app->request->post();
        $model = SystemExchangeRate::findOne($id);
        $model->from = $post['from'];
        $model->to = $post['to'];
        $model->rate = $post['rate'];
        if (!$model->save()){
            return $this->response(false, 'error');
        }
        return $this->response(true, 'success');
    }
}