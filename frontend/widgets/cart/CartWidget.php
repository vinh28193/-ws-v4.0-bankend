<?php


namespace frontend\widgets\cart;


use Yii;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Widget;
use yii\widgets\Pjax;
use common\components\cart\CartSelection;

class CartWidget extends Widget
{
    public $uuid;
    /**
     * @var array
     */
    public $items;

    public $headers = [];

    public $pjaxContainer = 'cartPjaxItems';

    public $pjaxClientOptions = [];

    public $tableOptions = [];

    public $headerRowOptions = [];

    public $rowOptions = [];

    private $_totalAmount = 0;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->headers = [
            'Sản phẩm', 'Số lượng', 'Giá tiền'
        ];
        $this->registerClientScript();
        Html::addCssClass($this->tableOptions, ['cart-table']);
        $this->items = array_map([$this, 'preItem'], $this->items);
        echo Html::beginTag('div', $this->options);

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
        $products = [];
        foreach ($order['products'] as $product) {
            $products[] = [
                'sku' => $product['sku'],
                'parent_sku' => $product['parent_sku'],
                'product_name' => $product['product_name'],
                'product_link' => $product['product_link'],
                'link_img' => $product['link_img'],
                'link_origin' => $product['link_origin'],
                'variations' => $product['variations'],
                'total_final_amount' => $product['total_price_amount_local'],
                'available_quantity' => $product['available_quantity'],
                'quantity_sold' => $product['quantity_sold'],
                'quantity' => $product['quantity_customer'],
                'weight' => $product['total_weight_temporary'],
            ];
        }
        return [
            'key' => $key,
            'selected' => $selected,
            'portal' => $order['portal'],
            'ordercode' => $order['ordercode'],
            'seller' => $order['seller'],
            'products' => $products
        ];
    }

    public function run()
    {
        parent::run();
        if (empty($this->items)) {
            echo $this->render('empty');
        } else {
            $pjaxOptions['id'] = $this->pjaxContainer;
            Pjax::begin([
                'options' => $pjaxOptions
            ]);
            echo $this->renderItems();
            Pjax::end();
        }
        echo Html::endTag('div');
    }

    protected function getClientOptions()
    {
        return ArrayHelper::merge([
            'uuid' => $this->uuid,
            'updateUrl' => Url::toRoute(['update']),
            'removeUrl' => Url::toRoute(['remove']),
            'paymentUrl' => Url::toRoute(['payment']),
        ], $this->clientOptions);
    }

    protected function registerClientScript()
    {
        $id = $this->options['id'];
        $options = Json::htmlEncode($this->getClientOptions());
        $view = $this->getView();
        CartAsset::register($view);
        $view->registerJs("jQuery('#$id').wsCart($options);");
        $view->registerJs("console.log($('#$id').wsCart('data'));");
    }

    protected function renderSummary()
    {
        $summary = count($this->items);
        return '<div class="title">Giỏ hàng của bạn <span>(' . $summary . ' sản phẩm)</span></div>';
    }

    public function renderItems()
    {
        return $this->render('content', [
            'items' => $this->items,
        ]);
//        $tableHeader = $this->renderTableHeader();
//        $tableBody = $this->renderTableBody();
//        $content = array_filter([
//            $tableHeader,
//            $tableBody,
//        ]);
//        return Html::tag('table', implode("\n", $content), $this->tableOptions);
    }

    protected function renderTableBody()
    {
        $items = $this->items;
        $selected = false;
        $rows = [];
        foreach ($items as $index => $item) {
            $rows[] = $this->renderTableRow($index, $selected, $item);
        }
        return "<tbody>\n" . implode("\n", $rows) . "\n</tbody>";
    }

    public function renderTableHeader()
    {
        $content = Html::tag('tr', implode('', $this->headers), $this->headerRowOptions);
        return "<thead>\n" . $content . "\n</thead>";
    }

    public function renderTableRow($key, $selected, $item)
    {
        $content = $this->render('_item', [
            'key' => $key,
            'selected' => $selected,
            'item' => $item
        ]);
        $options = $this->rowOptions;
        $options['data-key'] = $key;
        return Html::tag('tr', $content, $options);
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
