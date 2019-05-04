<?php


namespace frontend\widgets\alias;

use yii\bootstrap4\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class AliasProductSliderWidget extends Widget
{
    public $wsProductGroups;
    public $index;
    public $wsAliasItem;

    public $owlCarouselClientOptions = [
        'slideSpeed' => 300,
        'paginationSpeed' => 400,
        'loop' => true,
        'autoplay' => 1000,
        'items' => 4,
        'nav' => true,
        'dots'=> false
    ];
    public function init()
    {
        parent::init();
        $this->registerClientJs();
    }

    public function run()
    {
        parent::run();
        return $this->render("alias_product_slider", [
            'wsProductGroups' => $this->wsProductGroups,
            'index' => $this->index,
            'item' => $this->wsAliasItem,
        ]);
    }
    protected function registerClientJs(){
        $owlCarouselClientOptions = Json::htmlEncode($this->owlCarouselClientOptions);
        $id = $this->index;
        $js = "$('#$id').owlCarousel($owlCarouselClientOptions);";
        $this->getView()->registerJs($js);
    }

}