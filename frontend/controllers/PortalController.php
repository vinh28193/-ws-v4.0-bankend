<?php


namespace frontend\controllers;


use common\products\BaseProduct;
use frontend\modules\favorites\controllers\FavoriteObject as FavoriteObject;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use common\products\forms\ProductDetailFrom;
use common\lib\EbayProductGate;
use common\products\RelateProduct;
use common\lib\AmazonProductGate;

class PortalController extends FrontendController
{
    public $layout = '@frontend/views/layouts/portal';

    public $portal = BaseProduct::TYPE_EBAY;

    public function init()
    {
        parent::init();
        $this->site_title = Yii::t('frontend', 'Buy in Amazon, eBay');
        $this->site_name = Yii::t('frontend', 'Portal {portal}', [
            'portal' => $this->portal = BaseProduct::TYPE_EBAY ? 'eBay' : 'Amazon'
        ]);
        $this->site_description = Yii::t('frontend', 'Buy in Amazon, eBay & Top Stores US Viet Nam. Order easy & online payment.');
    }

    protected function ogMetaTag()
    {
        return ArrayHelper::merge(parent::ogMetaTag(), [
            'keyword' => Yii::t('frontend', 'weshop, ebay, amazon, ebay vn, amazon vn, buy ebay, buy amazon, buy usa, us shipping, watch, technological, electronics, high-tech, clothing, gadgets, accessories, jewelry, watches, beauty, cosmetic, health product, sport')
        ]);
    }

    public function defaultLayoutParams()
    {
        return ArrayHelper::merge(parent::defaultLayoutParams(), [
            'portal' => $this->portal,
        ]);
    }

    public function actionVP()
    {
        //Get All Favorite
        $_favorite = new FavoriteObject();

        $fingerprint = null;
        $post = $this->request->post();
        if (isset($post['fingerprint'])) {
            $fingerprint = $post['fingerprint'];
        }
        if (!Yii::$app->getRequest()->validateCsrfToken()) {
            return ['success' => false, 'message' => Yii::t('frontend', 'Form Security Alert'), 'data' => ['content' => '']];
        }

        $UUID = Yii::$app->user->getId();
        $uuid = isset($UUID) ? $UUID : $fingerprint;
        Yii::info(" Get Favorite");
        $_All_favorite = $_favorite->getfavorite($uuid);
        Yii::info([
            'all_favorite' => $_All_favorite,
        ], __CLASS__);

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($_All_favorite) {
            $view = $this->renderPartial('viewed_product', [
                'items' => $_All_favorite,
            ]);
            return ['success' => true, 'message' => 'get data success', 'data' => ['content' => $view]];
        } else {
            return ['success' => false, 'message' => 'no data', 'data' => ['content' => '']];
        }
    }

    public function actionF()
    {
        // Favorite
        Yii::info(" Favorite : start create favorite");
        $fingerprint = null;
        $post = $this->request->post();
        if (isset($post['fingerprint'])) {
            $fingerprint = $post['fingerprint'];
        }
        //$item = ArrayHelper::getValue($post,'item');
        $id = ArrayHelper::getValue($post, 'sku');
        Yii::info(" id sku : " . $id);
        $portal = ArrayHelper::getValue($post, 'portal');
        Yii::info(" portal: " . $portal);
        if (!Yii::$app->getRequest()->validateCsrfToken()) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => false, 'message' => Yii::t('frontend', 'Form Security Alert'), 'data' => ['content' => '']];
        }

        $UUID = Yii::$app->user->getId();
        $uuid = isset($UUID) ? $UUID : $fingerprint;

        $form = new ProductDetailFrom();
        $form->load($this->request->getQueryParams(), '');
        $form->id = $id;
        $form->type = $portal; //'ebay' , 'amazon'
        $item = $form->detail();
        Yii::info(" Gets Item details Favorite ");
        Yii::info([
            'item_name' => $item->item_name,
            'item' => $item,
            'form_detail_Favorite' => 'actionFavorite',
            'sku' => $id,
            'type' => $portal,
            'Error' => $form->getErrors(),
        ], __CLASS__);

        if ($item == false) {
            Yii::info(" Gets Item call gate Error : ");
            Yii::info([
                'item' => $item,
                'Error' => $form->getErrors(),
                'sku' => $id,
                'type' => $portal
            ], __CLASS__);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => false, 'message' => Yii::t('frontend', 'Error Get Gate'), 'data' => ['content' => '']];
        }

        /**
         * $category = $item->getCustomCategory();
         * if($portal == 'ebay'){
         * $relate_product_rs = EbayProductGate::paserSugget($item->item_id,$category ? [$category->alias] : []);
         * }
         * if($portal == 'amazon'){
         * // $relate_product_rs = AmazonProductGate::paserSugget($item->item_id,$category ? [$category->alias] : []);
         * }
         *
         * $relate_product = isset($relate_product_rs['data']) ? ArrayHelper::getValue($relate_product_rs['data'],'item') : [];
         * $item->relate_products = RelateProduct::setRelateProducts($relate_product);
         **/
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($uuid and $item != false) {
            $_favorite = new FavoriteObject();
            Yii::info(" start save Favorite ");
            $flar = $_favorite->create($item, $id, $uuid);
            if ($flar) {
                return 'create favo Success';
            } else {
                return 'Can not create favo';
            }
        } else if ($item == false or is_null($uuid)) {
            return 'something wrong user uu! or get item data';
        }

        /**
         * // Queue Favorite Save
         * /*
         * $UUID = Yii::$app->user->getId();
         * $uuid = isset($UUID) ? $UUID : $this->uuid;
         * $id = Yii::$app->queue->delay(30)->push(new Favorite([
         * 'obj_type' => $item,
         * 'obj_id' => $id,
         * 'UUID' => $UUID
         * ]));
         * // Check whether the job is waiting for execution.
         * Yii::info(" Check whether the job is waiting for execution : ".Yii::$app->queue->isWaiting($id));
         * // Check whether a worker got the job from the queue and executes it.
         * Yii::info(" Check whether a worker got the job from the queue and executes it : ". Yii::$app->queue->isReserved($id));
         * // Check whether a worker has executed the job.
         * Yii::info(" Check whether a worker has executed the job : ". Yii::$app->queue->isDone($id));
         **/
    }


    public function actionFavorite()
    {
        // Favorite
        Yii::info(" Favorite : start create favorite");
        $fingerprint = null;
        $post = $this->request->post();
        if (isset($post['fingerprint'])) {
            $fingerprint = $post['fingerprint'];
        }
        //$item = ArrayHelper::getValue($post,'item');
        $id = ArrayHelper::getValue($post, 'sku');
        Yii::info(" id sku : " . $id);
        $portal = ArrayHelper::getValue($post, 'portal');
        Yii::info(" portal: " . $portal);
        if (!Yii::$app->getRequest()->validateCsrfToken()) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => false, 'message' => Yii::t('frontend', 'Form Security Alert'), 'data' => ['content' => '']];
        }

        $UUID = Yii::$app->user->getId();
        $uuid = isset($UUID) ? $UUID : $fingerprint;

        $form = new ProductDetailFrom();
        $form->load($this->request->getQueryParams(), '');
        $form->id = $id;
        $form->type = $portal; //'ebay' , 'amazon'
        $item = $form->detail();
        Yii::info(" Gets Item details Favorite ");
        Yii::info([
            'item_name' => $item->item_name,
            'item' => $item,
            'form_detail_Favorite' => 'actionFavorite',
            'sku' => $id,
            'type' => $portal,
            'Error' => $form->getErrors(),
        ], __CLASS__);

        if ($item == false) {
            Yii::info(" Gets Item call gate Error : ");
            Yii::info([
                'item' => $item,
                'Error' => $form->getErrors(),
                'sku' => $id,
                'type' => $portal
            ], __CLASS__);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => false, 'message' => Yii::t('frontend', 'Error Get Gate'), 'data' => ['content' => '']];
        }

        /**
         * $category = $item->getCustomCategory();
         * if($portal == 'ebay'){
         * $relate_product_rs = EbayProductGate::paserSugget($item->item_id,$category ? [$category->alias] : []);
         * }
         * if($portal == 'amazon'){
         * // $relate_product_rs = AmazonProductGate::paserSugget($item->item_id,$category ? [$category->alias] : []);
         * }
         *
         * $relate_product = isset($relate_product_rs['data']) ? ArrayHelper::getValue($relate_product_rs['data'],'item') : [];
         * $item->relate_products = RelateProduct::setRelateProducts($relate_product);
         **/
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($uuid and $item != false) {
            $_favorite = new FavoriteObject();
            Yii::info(" start save Favorite ");
            $flar = $_favorite->create($item, $id, $uuid);
            if ($flar) {
                return 'create favo Success';
            } else {
                return 'Can not create favo';
            }
        } else if ($item == false or is_null($uuid)) {
            return 'something wrong user uu! or get item data';
        }

        /**
         * // Queue Favorite Save
         * /*
         * $UUID = Yii::$app->user->getId();
         * $uuid = isset($UUID) ? $UUID : $this->uuid;
         * $id = Yii::$app->queue->delay(30)->push(new Favorite([
         * 'obj_type' => $item,
         * 'obj_id' => $id,
         * 'UUID' => $UUID
         * ]));
         * // Check whether the job is waiting for execution.
         * Yii::info(" Check whether the job is waiting for execution : ".Yii::$app->queue->isWaiting($id));
         * // Check whether a worker got the job from the queue and executes it.
         * Yii::info(" Check whether a worker got the job from the queue and executes it : ". Yii::$app->queue->isReserved($id));
         * // Check whether a worker has executed the job.
         * Yii::info(" Check whether a worker has executed the job : ". Yii::$app->queue->isDone($id));
         **/
    }

}
