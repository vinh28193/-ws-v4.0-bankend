<?php

namespace api\controllers;


use yii\filters\AccessControl;
use common\models\db\Order;
use api\behaviours\Verbcheck;
use api\behaviours\Apiauth;

use Yii;



class OrderController extends RestController
{

    public function behaviors()
    {

        $behaviors = parent::behaviors();

        return $behaviors + [

           'apiauth' => [
               'class' => Apiauth::className(),
               'exclude' => [],
               'callback'=>[]
           ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [
                            'index'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['*'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => Verbcheck::className(),
                'actions' => [
                    'index' => ['GET', 'POST'],
                    'create' => ['POST'],
                    'update' => ['PUT'],
                    'view' => ['GET'],
                    'delete' => ['DELETE']
                ],
            ],

        ];
    }

    public function actionIndex()
    {
        $params = $this->post['search'];
        $response = Order::search($params);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        \Yii::$app->response->data  =   array_merge($response['data'], $response['info']);

    }

    public function actionCreate()
    {
        if (isset($this->post) !== null)  {
            $model = new Order;
            $model->attributes = $this->post;

            /***Todo -  Validate data model ***/

            if ($model->save()) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data  =   $model->attributes;
            } else {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data  =   $model->errors;
            }
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }

    }

    public function actionUpdate($id)
    {
        if ($id !== null)  {
            $model = $this->findModel($id);
            $model->attributes = $this->post;

            /***Todo -  Validate data model ***/

            if ($model->save()) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data  =   $model->attributes;
            } else {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data  =   $model->errors;
            }
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }

    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        Yii::$app->api->sendSuccessResponse($model->attributes);
    }

    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->api->sendSuccessResponse($model->attributes);
    }

    protected function findModel($id)
    {
        $query = Order::find();
        $query->where('[[id]] = :id',[':id' => $id]);
        $query->with([
            'products',
            'orderFees',
            'packageItems',
            'walletTransactions',
            'seller',
            'saleSupport' => function(\yii\db\ActiveQuery $q){
                $q->select(['username','email','id','status', 'created_at', 'created_at']);
            }
        ]);
        if (($model = $query->one()) !== null and  $id !== null)  {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }


}