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

class CartWidget extends Widget
{

    /**
     * @var array
     */
    public $items;

    public $updateAction = 'update';

    public $removeAction = 'remove';

    private $_totalAmount = 0;

    private $_totalPaidAmount = 0;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->removeAction = Url::toRoute($this->removeAction);
        $this->updateAction = Url::toRoute($this->updateAction);
        $this->registerClientScript();
        Pjax::begin([
            'options' => $this->options,
            'linkSelector' => false,
            'formSelector' => false,
            'timeout' => false,
            'enablePushState' => false
        ]);

    }

    /**
     * @param $item
     * @return array|bool
     */
    protected function preItem($item)
    {
        $key = ArrayHelper::getValue($item, '_id', '');
        $order = ArrayHelper::getValue($item, 'value');
        $selected = CartSelection::isExist(CartSelection::TYPE_SHOPPING, $key);
        if ($selected) {
            $this->_totalAmount += ArrayHelper::getValue($order, 'total_final_amount_local', 0);
        }
        return [
            'key' => $key,
            'order' => $order,
        ];
    }

    public function run()
    {
        parent::run();
        echo $this->renderCartBox();
        Pjax::end();
    }

    protected function getClientOptions()
    {

        return ArrayHelper::merge([
            'updateUrl' => $this->updateAction,
            'removeUrl' => $this->removeAction,
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

    protected function renderSummary()
    {
        $summary = count($this->items);
        return '<div class="title">Giỏ hàng của bạn <span>(' . $summary . ' sản phẩm)</span></div>';
    }

    protected function renderCartBox()
    {
        return $this->render('item', ['items' => $this->items]);
    }

    protected function renderBilling()
    {

        $content = Html::beginTag('ul', ['class' => 'billing', 'style' => 'margin-top:-10px']);
        $content .= '<li><div class="left">Giá trị đơn hàng:</div><div class="right">' . $this->getStoreManager()->showMoney($this->_totalAmount) . '</div></li>';
        $content .= Html::endTag('ul');
        return $content;
    }

    protected function renderItem($item)
    {

    }

    public function renderButtonBox()
    {
        return <<< HTML
<div class="btn-box">
    <button type="button" class="btn btn-continue">Tiếp tục mua hàng</button>
    <button type="button" class="btn btn-payment">Tiến hành thanh toán</button>
</div>
HTML;
    }


    private $_storeManager;

    private function getStoreManager()
    {
        if (!is_object($this->_storeManager)) {
            $this->_storeManager = Yii::$app->storeManager;
        }

        return $this->_storeManager;
    }
}