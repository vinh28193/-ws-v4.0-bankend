<?php

namespace api\controllers;


use yii\filters\AccessControl;
use common\models\Order;
use api\behaviours\Verbcheck;
use api\behaviours\Apiauth;

use Yii;



class OrderController extends RestController
{
    /*
     * ```php
     * public function behaviors()
     * {
     *     return [
     *         'access' => [
     *             'class' => \yii\filters\AccessControl::className(),
     *             'only' => ['create', 'update'],
     *             'rules' => [
     *                 // deny all POST requests
     *                 [
     *                     'allow' => false,
     *                     'verbs' => ['POST']
     *                 ],
     *                 // allow authenticated users
     *                 [
     *                     'allow' => true,
     *                     'roles' => ['@'],
     *                 ],
     *                 // everything else is denied
     *             ],
     *         ],
     *     ];
     * }
     */

    public  $page = 1;
    public  $limit = 20;

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
            $model = $this->findOrder($id);
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
       // Yii::$app->api->sendSuccessResponse($model);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        \Yii::$app->response->data  =   $model;
    }

    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->api->sendSuccessResponse($model->attributes);
    }

    protected function findModel($id)
    {
        $model = Order::find()
             ->with([
                'products',
                'orderFees',
                'packageItems',
                'walletTransactions',
                'seller',
                'saleSupport' => function(\yii\db\ActiveQuery $q){
                    $q->select(['username','email','id','status', 'created_at', 'created_at']);
                }
            ])
            ->where(['id' => $id] );

        if ( $id !== null)  {
            return $model->orderBy('created_at desc')->limit($this->limit)->offset($this->page* $this->limit - $this->limit)->asArray()->all();
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    protected function findOrder($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }


}