<?php


namespace frontend\widgets\cart;


use common\components\cart\CartHelper;
use Yii;
use yii\helpers\StringHelper;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Widget;
use yii\widgets\Pjax;
use common\helpers\WeshopHelper;
use common\products\BaseProduct;
use common\components\cart\CartSelection;

class PjaxCartWidget extends Widget
{

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->registerClientScript();
        Pjax::begin([
            'options' => $this->options,
            'linkSelector' => false,
            'formSelector' => false,
            'timeout' => false,
            'enablePushState' => false
        ]);

    }


    public function run()
    {
        parent::run();
        Pjax::end();
    }

    protected function getClientOptions()
    {

        return ArrayHelper::merge([
            'listUrl' => Url::toRoute('index'),
            'updateUrl' => Url::toRoute('update'),
            'removeUrl' => Url::toRoute('remove'),
            'paymentUrl' => Url::toRoute(['payment']),
        ], $this->clientOptions);
    }

    protected function registerClientScript()
    {
        $id = $this->options['id'];
        $options = Json::htmlEncode($this->getClientOptions());
        $view = $this->getView();
        CartAsset::register($view);
        $view->registerJs("jQuery('#$id').wsCart($options);", $view::POS_END);
        $view->registerJs("console.log($('#$id').wsCart('data'));", $view::POS_END);
    }

}