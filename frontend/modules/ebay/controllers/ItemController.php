<?php


namespace frontend\modules\ebay\controllers;

use common\helpers\WeshopHelper;
use common\lib\EbayProductGate;
use common\products\BaseProduct;
use common\products\RelateProduct;
use Yii;
use common\products\forms\ProductDetailFrom;
use  frontend\modules\favorites\controllers\FavoriteObject as FavoriteObject;
use  frontend\modules\favorites\controllers\FavoriteObjectQueue as Favorite;
use yii\helpers\ArrayHelper;



class ItemController extends EbayController
{

    public function actionDetail($id)
    {
        $form = new ProductDetailFrom();
        $form->load($this->request->getQueryParams(),'');
        $form->id = $id;
        $form->type = 'ebay';
        $item = $form->detail();
        if(Yii::$app->request->isPjax){
            if ($item === false) {
                return $this->renderAjax('@frontend/views/common/item_error', [
                    'errors' => $form->getErrors()
                ]);
            }
            return $this->renderAjax('index', [
                'item' => $item
            ]);
        }
        if ($item  === false) {
            return $this->render('@frontend/views/common/item_error', [
                'errors' => $form->getErrors()
            ]);
        }
        $category = $item->getCustomCategory();
        $relate_product_rs = EbayProductGate::paserSugget($item->item_id,$category ? [$category->alias] : []);
        $relate_product = isset($relate_product_rs['data']) ? ArrayHelper::getValue($relate_product_rs['data'],'item') : [];
        $item->relate_products = RelateProduct::setRelateProducts($relate_product);

        return $this->render('index', [
            'item' => $item
        ]);

    }


    public function actionViewedProducts()
    {
        //Get All Favorite
        $_favorite = new FavoriteObject();
        $UUID = Yii::$app->user->getId();
        $uuid = isset($UUID) ? $UUID : $this->uuid;
        $_All_favorite = $_favorite->getfavorite($uuid);

        echo "<pre>";
        print_r($_All_favorite);
        echo "</pre>";
        die("545345435345");

        return $this->render('index', [
            'item' => $_All_favorite,
        ]);
    }

    public function actionFavorite()
    {
        // Favorite
        $fingerprint = null;
        $post = $this->request->post();


        if (isset($post['fingerprint'])) {  $fingerprint = $post['fingerprint']; }
        if (isset($post['_csrf'])) {  $_csrf = $post['_csrf']; }
        $item = ArrayHelper::getValue($post,'item');
        $id = ArrayHelper::getValue($post,'sku');
        $UUID = Yii::$app->user->getId();
        $uuid = isset($UUID) ? $UUID : $fingerprint;
        if($uuid){
            $_favorite = new FavoriteObject();
            $_favorite->create($item, $id, $uuid);
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return 'Ok';

        /*
        // Queue Favorite Save
        /*
        $UUID = Yii::$app->user->getId();
        $uuid = isset($UUID) ? $UUID : $this->uuid;
        $id = Yii::$app->queue->delay(30)->push(new Favorite([
                'obj_type' => $item,
                'obj_id' => $id,
                'UUID' => $UUID
            ]));
        // Check whether the job is waiting for execution.
        Yii::info(" Check whether the job is waiting for execution : ".Yii::$app->queue->isWaiting($id));
        // Check whether a worker got the job from the queue and executes it.
        Yii::info(" Check whether a worker got the job from the queue and executes it : ". Yii::$app->queue->isReserved($id));
        // Check whether a worker has executed the job.
        Yii::info(" Check whether a worker has executed the job : ". Yii::$app->queue->isDone($id));
        */
/*
        $response = ['success' => false, 'message' => 'can not call', 'content' => []];
        $form = new ProductDetailFrom();
        $post = Yii::$app->getRequest()->post();
        if ($form->load(Yii::$app->getRequest()->post(), '')) {
            $response['message'] = 'can not resolve request';
        }
        $form->type = 'ebay';
        if (($item = $form->detail()) === false) {
            $response['message'] = 'failed';
            $response['content'] = $form->getErrors();
        } else {
            // @var $item BaseProduct
            $fees = [];
            foreach ($item->getAdditionalFees()->keys() as $key) {
                $fees[$key] = $item->getAdditionalFees()->getTotalAdditionFees($key)[1];
            }
            $response['success'] = true;
            $response['message'] = 'success';
            $contentPrice = '<strong class="text-orange">' . WeshopHelper::showMoney($item->getLocalizeTotalPrice(), 1, '') . '<span class="currency">đ</span></strong>';
            if ($item->start_price) {
                $contentPrice .= '<b class="old-price">' . WeshopHelper::showMoney($item->getLocalizeTotalStartPrice(), 1, '') . '<span class="currency">đ</span></b>';
                $contentPrice .= '<span class="save">(Tiết kiệm: ' . WeshopHelper::showMoney($item->getLocalizeTotalStartPrice() - $item->getLocalizeTotalPrice(), 1, '') . 'đ)</span>';
            }
            $response['content'] = [
                'fees' => $fees,
                'queryParams' => $post,
                'sellPrice' => $item->getLocalizeTotalPrice(),
                'startPrice' => $item->getLocalizeTotalStartPrice(),
                'salePercent' => $item->getSalePercent(),
                'contentPrice' => $contentPrice,
                'item' =>$item,
            ];
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
        */
    }

    public function actionVariation()
    {
        $response = ['success' => false, 'message' => 'can not call', 'content' => []];
        $form = new ProductDetailFrom();
        $post = Yii::$app->getRequest()->post();
        if ($form->load(Yii::$app->getRequest()->post(), '')) {
            $response['message'] = 'can not resolve request';
        }
        $form->type = 'ebay';
        if (($item = $form->detail()) === false) {
            $response['message'] = 'failed';
            $response['content'] = $form->getErrors();
        } else {
            /** @var $item BaseProduct */
            $fees = [];
            foreach ($item->getAdditionalFees()->keys() as $key) {
                $fees[$key] = $item->getAdditionalFees()->getTotalAdditionFees($key)[1];
            }
            $response['success'] = true;
            $response['message'] = 'success';
            $contentPrice = '<strong class="text-orange">' . WeshopHelper::showMoney($item->getLocalizeTotalPrice(), 1, '') . '<span class="currency">đ</span></strong>';
            if ($item->start_price) {
                $contentPrice .= '<b class="old-price">' . WeshopHelper::showMoney($item->getLocalizeTotalStartPrice(), 1, '') . '<span class="currency">đ</span></b>';
                $contentPrice .= '<span class="save">(Tiết kiệm: ' . WeshopHelper::showMoney($item->getLocalizeTotalStartPrice() - $item->getLocalizeTotalPrice(), 1, '') . 'đ)</span>';
            }
            $response['content'] = [
                'fees' => $fees,
                'queryParams' => $post,
                'sellPrice' => $item->getLocalizeTotalPrice(),
                'startPrice' => $item->getLocalizeTotalStartPrice(),
                'salePercent' => $item->getSalePercent(),
                'contentPrice' => $contentPrice,
            ];
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
    }
}
