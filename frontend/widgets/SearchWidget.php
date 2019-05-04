<?php

namespace frontend\widgets;

use frontend\assets\SearchAsset;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class SearchWidget extends Widget
{

    public $options = [];

    public $portals = ['amazon','ebay'];

    public $clientOptions = [

    ];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        parent::run();
        $id = $this->options['id'];
        $view = $this->getView();
        SearchAsset::register($view);
        $clientOptions = $options = Json::htmlEncode(array_merge($this->getClientOptions()));
        $view->registerJs("jQuery('#$id').wsSearch($clientOptions);");
        $view->registerJs("console.log($('#$id').wsSearch('queryParams'))");
        echo Html::tag('div','',$this->options);
    }

    protected function getClientOptions()
    {
        $request = Yii::$app->request;
        return [
            'absoluteUrl' => $request->absoluteUrl,
        ];
    }
}