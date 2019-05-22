<?php
namespace frontend\widgets\alias;

use common\components\Cache;
use common\models\cms\WsAlias;
use Yii;
use yii\base\Widget;

class TopMenuAliasWidget extends Widget
{
    public $type ;
    public function run()
    {
        $key = $this->type . '-' . Yii::$app->storeManager->getId();
        $view = null;Cache::get($key);
        if(!$view){
            $alias = WsAlias::findOne(['store_id'=>Yii::$app->storeManager->getId(),'type'=>$this->type]);
            $item_category = $alias->getCategoryList(false);
            $view = $this->render('top_menu_alias', [
                'categories'=>$item_category,
                'storeManager'=>Yii::$app->storeManager
            ]);
            Cache::set($key,$view,60*60*24);
        }
        return $view;
    }
}