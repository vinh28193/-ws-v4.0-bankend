<?php


namespace frontend\widgets\cart;


use common\components\cart\CartHelper;
use Yii;
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

    /**
     * @var bool
     */
    public $isGroup = false;

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
//        if ($this->isGroup) {
//            $this->items = CartHelper::group($this->items);
//        }
        $this->items = array_map([$this, 'preItem'], $this->items);
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
        $sellerId = isset($item['request']['seller']) ? $item['request']['seller'] : null;
        if ($sellerId === null || ($product = $this->isValidItem($item)) === false) {
            return false;
        }
        $provider = $sellerId;
        foreach ($product->providers as $p) {
            if (
                (strtoupper($product->type) === BaseProduct::TYPE_EBAY && $p->name === $provider) ||
                (strtoupper($product->type) !== BaseProduct::TYPE_EBAY && $p->prov_id === $provider)
            ) {
                $provider = $p;
                break;
            }
        }
        $imageSrc = isset($item['request']['image']) ? $item['request']['image'] : $product->current_image;

        list($amount, $localAmount) = $product->getAdditionalFees()->getTotalAdditionFees();

        return [
            'key' => ArrayHelper::getValue($item, 'key', ''),
            'selected' => true,
            'name' => $product->item_name,
            'type' => $product->type,
            'originLink' => $product->item_origin_url,
            'link' => WeshopHelper::generateUrlDetail($product->type, $product->item_name, $product->item_id, $product->item_sku, $sellerId),
            'imageSrc' => $imageSrc,
            'provider' => $provider,
            'variation' => $product->current_variation,
            'condition' => $product->condition,
            'quantity' => $product->getShippingQuantity(),
            'availableQuantity' => $product->available_quantity,
            'soldQuantity' => $product->quantity_sold,
            'weight' => $product->getShippingWeight(),
            'amount' => $amount,
            'localAmount' => $localAmount,
            'localDisplayAmount' => $this->showMoney($localAmount),
        ];
    }

    public function run()
    {
        parent::run();
        $html = $this->renderSummary();
        $html .= Html::tag('div', $this->renderItems() . $this->renderBilling(), ['class' => 'cart-box']);
        $html .= '<p class="text-right note">* Quý khách nên thanh toán ngay để tránh sản phẩm bị tăng giá</p>';
        $html .= $this->renderButtonBox();
        echo $html;
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
        $items = Json::htmlEncode(array_values($this->items));
        CartAsset::register($view);
        $view->registerJs("jQuery('#$id').wsCart($items,$options);", $view::POS_END);
        $view->registerJs("console.log($('#$id').wsCart('data'));", $view::POS_END);
    }

    protected function renderSummary()
    {
        $summary = count($this->items);
        return '<div class="title">Giỏ hàng của bạn <span>(' . $summary . ' sản phẩm)</span></div>';
    }

    protected function renderGroupItems()
    {

    }

    protected function renderItems()
    {
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $this->renderItem($item);
        }
        return Html::tag('ul', implode("\n", $items), ['class' => 'cart-item']);
    }

    protected function renderBilling()
    {

        $content = Html::beginTag('ul', ['class' => 'billing']);
        $content .= '<li><div class="left">Giá trị đơn hàng:</div><div class="right">' . $this->showMoney($this->_totalAmount) . ' <i class="currency">đ</i></div></li>';
        $content .= Html::endTag('ul');
        return $content;
    }

    protected function renderItem($item)
    {
        if ($item === false) {
            return Html::tag('li', 'Invalid Item');
        }
        return $this->render('item', $item);
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

    /**
     * @param $item
     * @return bool|BaseProduct
     */
    private function isValidItem($item)
    {
        return !(($product = ArrayHelper::getValue($item, 'response')) === null || !$product instanceof BaseProduct) ? $product : false;
    }

    private function showMoney($money)
    {
        return Yii::$app->storeManager->showMoney($money);
    }
}