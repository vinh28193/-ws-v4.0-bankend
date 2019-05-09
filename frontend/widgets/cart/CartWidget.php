<?php


namespace frontend\widgets\cart;

use common\products\BaseProduct;
use Yii;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Widget;

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

    public $updateAction = 'cart/update';

    public $removeAction = 'cart/remove';
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        if ($this->isGroup) {
//            $this->items = ArrayHelper::index($this->items, 'key', function ($item) {
//                $request = $item['request'];
//                return $request['type'] . ':' . $request['seller'];
//            });
        }

    }

    public function run()
    {
        parent::run();
        $html = Html::beginTag('div', $this->options);
        $html .= $this->renderSummary();
        $html .= Html::tag('div', $this->renderItems(), ['class' => 'cart-box']);
        $html .= Html::endTag('div');
        return $html;
    }

    protected function registerClientScript()
    {

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

    protected function renderItem($item)
    {
        $sellerId = isset($item['request']['seller']) ? $item['request']['seller'] : null;
        if ($sellerId === null || ($product = ArrayHelper::getValue($item, 'response')) === null || !$product instanceof BaseProduct) {
            return Html::tag('li', 'Invalid Item');
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
        return $this->render('item', [
            'key' => ArrayHelper::getValue($item,'key',''),
            'name' => $product->item_name,
            'type' => $product->type,
            'originLink' => $product->item_origin_url,
            'link' => '#',
            'imageSrc' => $imageSrc,
            'provider' => $provider,
            'variation' => $product->current_variation,
            'quantity' => $product->quantity,
            'availableQuantity' => $product->available_quantity,
            'soldQuantity' => $product->quantity_sold,
            'weight' => $product->shipping_weight,
            'price' => $product->getLocalizeTotalPrice(),
        ]);

    }
}