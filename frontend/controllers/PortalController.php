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

    public $portalTitle;
    public $portalImage;
    public $portalDescription;

    public function init()
    {
        parent::init();
        $this->portalTitle = Yii::t('frontend', 'Buy in Amazon, eBay');
        $this->portalImage = '';
        $this->portalDescription = Yii::t('frontend', 'Buy in Amazon, eBay & Top Stores US Viet Nam. Quality assurance. Order easy & online payment. free ship local.');
    }

    public function ogMetaTag()
    {
        return ArrayHelper::merge(parent::ogMetaTag(), [
            'title' => $this->portalTitle,
            'image' => $this->portalImage,
            'description' => $this->portalDescription,
            'portal' => strtolower($this->portal),
            'keyword' => Yii::t('frontend', 'weshop, ebay, amazon, ebay vn, amazon vn, buy ebay, buy amazon, buy usa, us shipping, watch, technological, electronics, high-tech, clothing, gadgets, accessories, jewelry, watches, beauty, cosmetic, health product, sport')
        ]);
    }

    public function defaultLayoutParams()
    {
        return ArrayHelper::merge(parent::defaultLayoutParams(), [
            'portal' => $this->portal,
        ]);
    }

    public function actionViewedProducts()
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
        $_All_favorite = $_favorite->getfavorite($uuid);
        Yii::$app->response->format = Response::FORMAT_JSON;
        $_cou = (array)$_All_favorite;
        if (@count($_cou)) {
            $view = $this->renderPartial('viewed_product', [
                'items' => $_All_favorite,
            ]);
            return ['success' => true, 'message' => 'get data success', 'data' => ['content' => $view]];
        } else {
            return ['success' => false, 'message' => 'no data', 'data' => ['content' => '']];
        }
    }

    public function actionFavorite()
    {
        // Favorite
        Yii::info(" Favorite : app  create favorite Success");

        $fingerprint = null;
        $post = $this->request->post();
        if (isset($post['fingerprint'])) {
            $fingerprint = $post['fingerprint'];
        }
        //$item = ArrayHelper::getValue($post,'item');
        $id = ArrayHelper::getValue($post, 'sku');
        Yii::info(" id : " . $id);
        $portal = ArrayHelper::getValue($post, 'portal');
        Yii::info(" portal: " . $portal);

        Yii::info(" Favorite : app  create favorite Success 02 ");
        if (!Yii::$app->getRequest()->validateCsrfToken()) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => false, 'message' => Yii::t('frontend', 'Form Security Alert'), 'data' => ['content' => '']];
        }

        Yii::info(" Favorite : app  create favorite Success 03 ");
        $UUID = Yii::$app->user->getId();
        $uuid = isset($UUID) ? $UUID : $fingerprint;

        $form = new ProductDetailFrom();
        $form->load($this->request->getQueryParams(), '');
        $form->id = $id;
        $form->type = $portal; //'ebay' , 'amazon'
        $item = $form->detail();


        Yii::info(" Favorite : app  create favorite Success 04");

        if ($item == false) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => false, 'message' => Yii::t('frontend', 'Error Get Ebay Gate'), 'data' => ['content' => '']];
        }


        Yii::info(" Favorite : app  create favorite Success 05");

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

        Yii::info(" Favorite : app  create favorite Success");
        if ($uuid) {
            $_favorite = new FavoriteObject();
            $_favorite->create($item, $id, $uuid);
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return 'Ok';

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
