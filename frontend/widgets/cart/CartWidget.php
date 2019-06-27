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

    public $totalAmount = 0;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        Html::addCssClass($this->tableOptions, ['cart-table']);
        $this->items = array_map([$this, 'preItem'], $this->items);
        $this->registerClientScript();
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
        $type = ArrayHelper::getValue($item, 'type', CartSelection::TYPE_SHOPPING);
        $selected = CartSelection::isExist(CartSelection::TYPE_SHOPPING, $key);

        $products = [];
        $totalUsFee = 0;
        foreach ($order['products'] as $product) {
            if ($selected) {
                $this->totalAmount += $product['total_final_amount_local'];
            }
            $fees = [];
            foreach ($product['additionalFees'] as $name => $additionalFee) {
                if ($name === 'product_price') {
                    continue;
                }
                $amount = 0;
                foreach ($additionalFee as $row) {
                    $amount += $row['local_amount'];
                    if ($name === 'shipping_fee' || $name === 'tax_fee') {
                        $totalUsFee += $row['local_amount'];
                    }
                }
                $fees[$name] = $amount;
            }
            $products[] = [
                'sku' => $product['sku'],
                'parent_sku' => $product['parent_sku'],
                'product_name' => $product['product_name'],
                'product_link' => $product['product_link'],
                'link_img' => $product['link_img'],
                'link_origin' => $product['link_origin'],
                'variations' => $product['variations'],
                'total_unit_amount' => $product['price_amount_local'],
                'total_us_fee' => $totalUsFee,
                'total_final_amount' => $product['total_final_amount_local'],
                'available_quantity' => $product['available_quantity'],
                'quantity_sold' => $product['quantity_sold'],
                'quantity' => $product['quantity_customer'],
                'weight' => $product['total_weight_temporary'],
                'fees' => $fees
            ];
        }
        return [
            'key' => $key,
            'selected' => $selected,
            'portal' => $order['portal'],
            'final_amount' => $order['total_final_amount_local'],
            'type' => $type,
            'ordercode' => $order['ordercode'],
            'seller' => $order['seller'],
            'products' => $products
        ];
    }

    public function run()
    {
        parent::run();
        $pjaxOptions['id'] = $this->pjaxContainer;
        Pjax::begin([
            'options' => $pjaxOptions
        ]);
        if (empty($this->items)) {
            echo $this->render('empty');
        }
        echo $this->renderItems();
        Pjax::end();
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
        $items = Json::htmlEncode($this->items);
        $view = $this->getView();
        CartAsset::register($view);
        $view->registerJs("jQuery('#$id').wsCart($items,$options);");
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
            'totalAmount' => $this->totalAmount
        ]);
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


    private $_storeManager;

    private function getStoreManager()
    {
        if (!is_object($this->_storeManager)) {
            $this->_storeManager = Yii::$app->storeManager;
        }

        return $this->_storeManager;
    }
}
