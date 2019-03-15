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

            $model->attributes = [
                'order_id' => 2,
                'seller_id' => 2,
                'portal' => 'AMAZON_JAPAN',
                'sku' => 'db5e5333ff544da2a6a6',
                'product_name' => 'The players all played at once to eat the comfits: this caused some noise and confusion, as the hall was very hot, she kept on puzzling about it in a few minutes it seemed quite natural to Alice as.',
                'parent_sku' => '34efc0d2c247db5e5333ff544da2a6',
                'link_img' => 'https://lorempixel.com/640/480/?70454',
                'link_origin' => 'http://www.moc.com/quaerat-repellendus-id-autem-nulla-harum-fuga.html',
                'category_id' => 2,
                'custom_category_id' => 2,
                'price_amount_origin' => 3,
                'quantity_customer' => 3,
                'price_amount_local' => 70500,
                'total_price_amount_local' => 70500,
                'quantity_purchase' => 0,
                'quantity_inspect' => 0,
                'variations' => '',
                'variation_id' => '',
                'note_by_customer' => 'Let me see: four.',
                'total_weight_temporary' => 0.5,
                'created_at' => 1540486574,
                'updated_at' => 498739696,
                'remove' => 0,
            ];

            var_dump($model->validate());
            var_dump($model->errors);
            die();

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
                'packageItems',
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
