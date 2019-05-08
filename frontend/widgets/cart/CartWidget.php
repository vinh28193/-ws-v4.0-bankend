<?php


namespace frontend\widgets\cart;

use Yii;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Widget;

class CartWidget extends Widget
{

    public $items;

    public $isGroup = false;

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
        $html .= Html::tag('div',$this->renderItems(),['class' => 'cart-box']);
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
        foreach ($this->items as $item){
            $items[] = $this->renderItem($item);
        }
        return Html::tag('ul',implode("\n",$items),['class' => 'cart-item']);
    }

    protected function renderItem($item)
    {
        return <<< HTML
<li>
    <div class="thumb">
        <img src="https://images-na.ssl-images-amazon.com/images/I/51aLZ8NqnaL.jpg" alt="">
    </div>
    <div class="info">
        <div class="left">
            <a href="#" class="name">Citizen Eco-Drive Women's GA10580-59Q Axiom Diamond Pink Gold-Tone 30mm Watch</a>
            <div class="rate">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
                <i class="far fa-star"></i>
            </div>
            <ol>
                <li>Bán bởi: <a href="#">Multiple supplier.</a>/ New</li>
                <li>Band Color: red</li>
                <li>Tạm tính: 0.45 kg</li>
            </ol>
        </div>
        <div class="right">
            <div class="qty form-inline">
                <label>Số lượng:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary" type="button">-</button>
                    </div>
                    <input type="text" class="form-control" value="1" aria-label="" aria-describedby="basic-addon1">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button">+</button>
                    </div>
                </div>
            </div>
            <div class="price">5.800.000 <i class="currency">đ</i></div>
            <a href="#" class="del"><i class="far fa-trash-alt"></i> Xóa</a>
        </div>
    </div>
</li>
HTML;

    }
}