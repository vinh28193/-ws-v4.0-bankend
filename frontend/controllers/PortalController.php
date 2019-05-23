<?php


namespace frontend\controllers;


use common\products\BaseProduct;
use frontend\modules\favorites\controllers\FavoriteObject as FavoriteObject;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

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
        $UUID = Yii::$app->user->getId();
        $uuid = isset($UUID) ? $UUID : $this->uuid;
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
//        return $view;
    }
}
