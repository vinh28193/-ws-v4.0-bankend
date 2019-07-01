<?php
namespace frontend\widgets\alias;

use common\components\Cache;
use common\models\cms\WsAlias;
use Yii;
use yii\base\Widget;

class TopMenuAliasWidget extends Widget
{
    public $type ;
    public $logoMobile = '' ;
    public $type_monitor = 'pc' ;
    public function run()
    {
        $key = 'category_'.$this->type . '-' . Yii::$app->storeManager->getId();
        $item_category = Cache::get($key);
        if(!$item_category){
            $alias = WsAlias::findOne(['store_id'=>Yii::$app->storeManager->getId(),'type'=>$this->type]);
//                        $alias = WsAlias::findOne(['store_id'=>1,'type'=>$this->type]);
            $item_category = $alias->getCategoryList(false);
            Cache::set($key,$item_category,60*60*24);
        }
        $view = $this->render('top_menu_alias-'.$this->type_monitor, [
            'categories'=>$item_category,
            'logoMobile'=>$this->logoMobile,
            'type'=>$this->type,
            'storeManager'=>Yii::$app->storeManager
        ]);
        return $view;
    }
}