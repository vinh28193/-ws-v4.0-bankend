<?php


namespace frontend\widgets\breadcrumb;


use common\components\StoreManager;
use common\models\Category;
use Yii;
use yii\base\Widget;

class BreadcrumbWidget extends Widget
{
    public $portal = 'Home';
    public $params = [];
    public function run()
    {
        return $this->render('breadcrumb',['portal' => $this->portal,'params' => $this->params]);
    }

    public static function GenerateCategory($cate){
        $cates = explode(',',$cate);
        $data = [];
        /** @var $storeM StoreManager */
        $storeM = Yii::$app->storeManager;
        foreach ($cates as $k => $c ){
            if(count($cates) - $k <= 3 && $c){
                $category = Category::getAlias($c);
                if($category){
                    if($storeM->isVN()){
                        $data[$category->name] = 'javascript:void(0);';
                    }else{
                        $data[$category->origin_name] = 'javascript:void(0);';
                    }
                }
            }
        }
        return $data;
    }
}