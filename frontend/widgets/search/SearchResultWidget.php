<?php

namespace frontend\widgets\search;

use frontend\assets\SearchAsset;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class SearchResultWidget extends Widget
{
    public $results = [];
    public $options = [];

    public $portal = 'ebay';

    public $clientOptions = [

    ];

    public function init()
    {
        parent::init();
        $this->options = ArrayHelper::merge([
            'data-action' => $this->getClientOptions()['absoluteUrl'],
            'data-portal' => $this->portal
        ], $this->options);
        Html::addCssClass($options, "{$this->portal}-search");
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
        return Html::tag('div', $this->renderResults(), $this->options);
    }

    protected function getClientOptions()
    {
        return [
            'typeOfSearch' => 'keyword',
            'absoluteUrl' => Yii::$app->request->absoluteUrl,
            'enableFilter' => true,
            'filterParam' => 'filter',
            'portal' => $this->portal
        ];
    }

    protected function renderResults()
    {
        $results = Html::beginTag('div', ['class' => 'row']);
        $results .= Html::tag('div', $this->renderLeft(), [
            'class' => 'col-md-3'
        ]);
        $results .= Html::tag('div', $this->renderRight(), [
            'class' => 'col-md-9'
        ]);

        $results .= Html::endTag('div');
        return $results;
    }

    protected function renderLeft()
    {
        return $this->render('left', [
            'categories' => ArrayHelper::getValue($this->results, 'categories', []),
            'filters' => ArrayHelper::getValue($this->results, 'filters', [])
        ]);
    }

    protected function renderRight()
    {
        return $this->render('right', [
            'products' => ArrayHelper::getValue($this->results, 'products', []),
        ]);
    }
}