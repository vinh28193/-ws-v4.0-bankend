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

    public function defaultLayoutParams()
    {
        return ArrayHelper::merge(parent::defaultLayoutParams(),[
            'portal' => $this->portal,
        ]);
    }
    public function actionViewedProducts()
    {
        //Get All Favorite
        $_favorite = new FavoriteObject();

        $fingerprint = null;
        $post = $this->request->post();
        if (isset($post['fingerprint'])) {  $fingerprint = $post['fingerprint']; }
        if (!Yii::$app->getRequest()->validateCsrfToken()) {
            return ['success' => false,'message' => 'Form Security Alert', 'data' => ['content' => ''] ];
        }

        $UUID = Yii::$app->user->getId();
        $uuid = isset($UUID) ? $UUID : $fingerprint;
        $_All_favorite = $_favorite->getfavorite($uuid);
        Yii::$app->response->format = Response::FORMAT_JSON;

        if(count($_All_favorite)){
            $view = $this->renderPartial('viewed_product', [
                'items' => $_All_favorite,
            ]);
            return ['success' => true,'message' => 'lấy thành công', 'data' => ['content' => $view] ];
        }else{
            return ['success' => false,'message' => 'Không có dữ liệu', 'data' => ['content' => ''] ];
        }
    }

    public function actionFavorite()
    {
        // Favorite
        $fingerprint = null;
        $post = $this->request->post();
        if (isset($post['fingerprint'])) {  $fingerprint = $post['fingerprint']; }
        $item = ArrayHelper::getValue($post,'item');
        $id = ArrayHelper::getValue($post,'sku');
        $portal =  ArrayHelper::getValue($post,'portal');

        if (!Yii::$app->getRequest()->validateCsrfToken()) {
            return ['success' => false,'message' => 'Form Security Alert', 'data' => ['content' => ''] ];
        }

        $UUID = Yii::$app->user->getId();
        $uuid = isset($UUID) ? $UUID : $fingerprint;

        $form = new ProductDetailFrom();
        $form->load($this->request->getQueryParams(),'');
        $form->id = $id;
        $form->type = $portal ; //'ebay' , 'amazon'
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
        if($portal == 'ebay'){
            $relate_product_rs = EbayProductGate::paserSugget($item->item_id,$category ? [$category->alias] : []);
        }
        if($portal == 'amazon'){
           // $relate_product_rs = AmazonProductGate::paserSugget($item->item_id,$category ? [$category->alias] : []);
        }

        $relate_product = isset($relate_product_rs['data']) ? ArrayHelper::getValue($relate_product_rs['data'],'item') : [];
        $item->relate_products = RelateProduct::setRelateProducts($relate_product);


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
    }


}
