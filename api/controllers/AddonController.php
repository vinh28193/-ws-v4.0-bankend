<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 4/3/2019
 * Time: 5:12 PM
 */

namespace api\controllers;
use common\models\ListAccountPurchase;
use common\models\Order;
use Yii;
use api\controllers\BaseApiController;


class AddonController extends BaseApiController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $auth = $behaviors['authenticator'];
        $except = array_merge($auth['except'],[
            'index',
            'order-list',
            'view'
        ]);
        $auth['except'] = $except;
        $behaviors['authenticator'] = $auth;
        return $behaviors;
    }

    protected function rules()
    {
        return [
            [
                'actions' => ['authorize', 'register','access-token','index', 'order-list','view'],
                'allow' => true
            ],
            [
                'actions' => ['signup'],
                'allow' => true,
                'roles' => ['?'],
            ],
            [
                'actions' => ['logout', 'me'],
                'allow' => true,
                'roles' => ['@'],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['POST','GET'],
            'order-list' => ['POST','GET'],
            'view' => ['POST','GET'],
        ];
    }


    public function actionIndex()
    {
        $model = ListAccountPurchase::find()->asArray()->all();
        if ($model) {
            return $this->response(true, 'success', $model);
        }
        return $this->response(false, 'not found account purchase');
    }


    public function actionOrderList() {
        $post = Yii::$app->request->get();
        $model = Order::find()
            ->where(['seller_id'=> $post['sellerId']])
            ->with('products')
            ->asArray()->all();
        if ($model) {
            return $this->response(true, 'success', $model);
        }
        return $this->response(false, 'not found order');
    }

    public function actionView($id) {
        //ToDo Set Header
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        if ($id !== null) {
            $query = Order::find();
            $request = $query->where([$query->getColumnName('id') => $id])
                ->withFullRelations()
                ->asArray()->all();
            return $this->response(true, 'success', $request);
            //return $this->response(true, "Get order $id success", $request);
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }

    }
}
