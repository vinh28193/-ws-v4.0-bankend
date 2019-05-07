<?php

namespace frontend\widgets\search;

use frontend\assets\SearchAsset;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class SearchResultWidget extends Widget
{

    public $options = [];

    public $portals = ['amazon', 'ebay'];

    public $clientOptions = [

    ];

    public function init()
    {
        parent::init();
        if (is_string($this->portals)) {
            $this->portals = [$this->portals];
        }
        $this->options['data-action'] = $this->getClientOptions()['absoluteUrl'];
    }

    public function run()
    {
        parent::run();
        $id = $this->options['id'];
        $view = $this->getView();
        SearchAsset::register($view);
        $options = Json::htmlEncode($this->getClientOptions());
        $view->registerJs("jQuery('#$id').wsSearch($options);");
        $view->registerJs("console.log($('#$id').wsSearch('data'))");
        return $this->renderPortals();
    }

    protected function getClientOptions()
    {
        return [
            'typeOfSearch' => 'keyword',
            'absoluteUrl' => Yii::$app->request->absoluteUrl,
            'enableFilter' => true,
            'filterParam' => 'filter',
            'portals' =>$this->portals
        ];
    }

    protected function renderPortals()
    {
        $content = Html::beginTag('div', $this->options);
        foreach ($this->portals as $name) {
            $options = [
                'data-portal' => $name,
            ];
            Html::addCssClass($options, "$name-search");
            $content .= Html::tag('div', '', $options);
        }
        $content .= Html::endTag('div');
        return $content;
    }

}