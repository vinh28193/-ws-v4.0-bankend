<?php

namespace api\controllers;

use yii\filters\AccessControl;
use common\models\Product;
use api\behaviours\Verbcheck;
use api\behaviours\Apiauth;

use Yii;


class ProductController extends BaseApiController
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



    public function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'create' => ['POST'],
            'update' => ['PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE']
        ];
    }

    public function actionIndex()
    {
        $params = '';
        $response = Product::search($params);
        Yii::$app->api->sendSuccessResponse($response);
    }

    public function actionCreate()
    {
        if (isset($this->post) !== null)  {
            $model = new Product;
            $model->attributes = $this->post;
            if ($model->save()) {
                /* \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; \Yii::$app->response->data  =   $model->attributes; */
                  Yii::$app->api->sendSuccessResponse($model->attributes);
            } elseif ($model->save() === false) {
                /* \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; \Yii::$app->response->data  =   $model->errors;  */
                Yii::$app->api->sendFailedResponse("Invalid Record requested" , (array)$model->errors);
            }
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }

    }

    public function actionUpdate($id)
    {
        if ($id !== null)  {
            $model = $this->findProduct($id);
            $model->attributes = $this->post;
            /***Todo -  Validate data model ***/
            if ($model->save()) {
                Yii::$app->api->sendSuccessResponse($model->attributes);
            } else {
                Yii::$app->api->sendFailedResponse("Invalid Record requested" , (array)$model->errors);
            }
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }

    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        Yii::$app->api->sendSuccessResponse($model);
    }

    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->api->sendSuccessResponse($model->attributes);
    }

    protected function findModel($id)
    {
        $model = Product::find()
             ->with([
                'products',
                'promotion',
                'ProductFees',
                'packages',
                'walletTransactions',
                'seller',
                'saleSupport' => function(\yii\db\ActiveQuery $q){
                    $q->select(['username','email','id','status', 'created_at', 'created_at']);
                }
            ])
            ->where(['id' => $id] );

        if ( $id !== null)  {
            return $model->ProductBy('created_at desc')->limit($this->limit)->offset($this->page* $this->limit - $this->limit)->asArray()->all();
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    protected function findProduct($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }


}
