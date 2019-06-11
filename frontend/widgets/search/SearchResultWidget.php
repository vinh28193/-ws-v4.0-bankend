<?php

namespace frontend\widgets\search;

use common\components\StoreManager;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use common\products\forms\ProductSearchForm;


/**
 * Class SearchResultWidget
 * @package frontend\widgets\search
 */
class SearchResultWidget extends Widget
{
    /**
     * @var array
     */
    public $results = [];
    /**
     * @var ProductSearchForm
     */
    public $form;
    /**
     * @var array
     */
    public $options = [];

    public $portal;

    /**
     * @var array
     */
    public $clientOptions = [

    ];

    /**
     * @var StoreManager
     */
    public $storeManager;

    /**
     * @return StoreManager
     */
    public function getStoreManager()
    {
        if (!is_object($this->storeManager)) {
            $this->storeManager = Yii::$app->storeManager;
        }
        return $this->storeManager;
    }

    public function init()
    {
        parent::init();
        $this->options = ArrayHelper::merge([
            'data-action' => $this->getClientOptions()['absoluteUrl'],
            'data-portal' => $this->portal
        ], $this->options);
        if (!$this->portal) {
            $this->portal = $this->form->type;
        }
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
        if (strtolower($this->portal) === 'ebay') {
            $view->registerJs("setInterval(function () {ws.countdownTime();}, 1000);");
        }
        return Html::tag('div', $this->renderResults(), $this->options);
    }

    protected function getClientOptions()
    {
        return ArrayHelper::merge([
            'typeOfSearch' => 'keyword',
            'absoluteUrl' => Yii::$app->request->absoluteUrl,
            'enableFilter' => true,
            'filterParam' => 'filter',
            'portal' => $this->portal
        ], $this->clientOptions);
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
            'portal' => $this->portal,
            'category' => $this->form->category,
            'filter' => $this->form->filter,
            'categories' => ArrayHelper::getValue($this->results, 'categories', []),
            'filters' => ArrayHelper::getValue($this->results, 'filters', []),
            'conditions' => ArrayHelper::getValue($this->results, 'conditions', []),
        ]);
    }

    protected function renderRight()
    {
        return $this->render('right', [
            'portal' => $this->portal,
            'page' => Yii::$app->request->get('page', 1),
            'keyword' => $this->form->keyword,
            'total_product' => ArrayHelper::getValue($this->results, 'total_product', 0),
            'total_page' => ArrayHelper::getValue($this->results, 'total_page', 0),
            'item_per_page' => ArrayHelper::getValue($this->results, 'item_per_page', 0),
            'products' => ArrayHelper::getValue($this->results, 'products', []),
            'sorts' => ArrayHelper::getValue($this->results, 'sorts', []),
            'storeManager' => $this->getStoreManager(),
        ]);
    }
}