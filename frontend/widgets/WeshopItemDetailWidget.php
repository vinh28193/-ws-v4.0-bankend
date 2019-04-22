<?php


namespace frontend\widgets;

use Yii;
use frontend\assets\ItemAsset;
use yii\bootstrap\Widget;
use common\products\BaseProduct;
use yii\base\InvalidConfigException;
use yii\bootstrap4\Dropdown;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

class WeshopItemDetailWidget extends Widget
{

    /**
     * @var BaseProduct
     */
    public $item;

    /**
     * Initializes the object.
     * This method is called at the end of the constructor.
     * The default implementation will trigger an [[EVENT_INIT]] event.
     */
    public function init()
    {
        parent::init();
        if ($this->item === null || !$this->item instanceof BaseProduct) {
            throw new InvalidConfigException(get_class($this) . "::product must be instance of: " . BaseProduct::className());
        }
        $this->prepareItem();
    }

    public function run()
    {
        parent::run();
        $this->registerClientScript();
        echo $this->renderEntries();
    }

    protected function prepareItem()
    {

    }

    public function getClientOptions()
    {
        $options = [
            'variation_mapping' => $this->item->variation_mapping,
            'variation_options' => $this->item->variation_options,
            'sellers' => $this->item->providers,
            'conditions' => $this->item->condition,
            'images' => $this->item->primary_images,
        ];
        return $options;
    }

    /**
     * This registers the necessary JavaScript code.
     * @since 2.0.12
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];
        $options = Json::htmlEncode($this->getClientOptions());
        $view = $this->getView();
        ItemAsset::register($view);
        $view->registerJs("jQuery('#$id').wsItem($options);", $view::POS_END);
        $view->registerJs("console.log($('#$id').wsItem('data'))", $view::POS_END);
    }

    protected function renderEntries()
    {
        $content = Html::tag('div', $this->renderDetailBlock(), [
            'class' => 'col-md-9'
        ]);
        $content .= $this->renderSlide();
        return Html::tag('div', $content, $this->options);
    }

    protected function renderDetailBlock()
    {
        $detail = Yii::$app->controller->renderPartial('_slider');
        $detail .= $this->renderFullInfo();
        return Html::tag('div', $detail, ['class' => 'detail-block']);
    }

    public function renderSlider()
    {
        return Yii::$app->controller->renderPartial('_slider', [
            'images' => $this->item->primary_images
        ]);
    }

    public function renderFullInfo()
    {
        $html = '<a href="#" class="brand">Bulova</a>';
        $title = Html::tag('h2', $this->item->item_name);
        if (($salePercent = $this->item->getSalePercent()) > 0) {
            $title .= ' <span class="sale-tag">' . $salePercent . '% OFF</span>';
        }
        $html .= Html::tag('div', $title, ['class' => 'title']);
        $html .= '<div class="rating">
                       <div class="rate text-orange">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                       </div>
                       <span>87 người đánh giá</span>
                  </div>';
        $price = Html::tag('strong', $this->item->getLocalizeTotalPrice() . Html::tag('span', 'đ', ['class' => 'currency']), ['class' => 'text-orange']);
        if ($salePercent > 0 && ($startPrice = $this->item->getLocalizeTotalStartPrice()) > 0) {
            $price .= Html::tag('b', $this->item->getLocalizeTotalStartPrice() . Html::tag('span', 'đ', ['class' => 'currency']), ['class' => 'old-price']);
            $price .= Html::tag('span', $salePercent, ['class' => 'save']);
        }
        $html .= Html::tag('div', $price, ['class' => 'price']);
        $html .= $this->renderOptionBox();
        $html .= '<ul class="info-list">
                <li>Imported</li>
                <li>Due to a recent redesign by Bulova, recently manufactured Bulova watches,including all watches sold and shipped by Amazon, will not feature the Bulova tuning fork logo on the watch face.</li>
                <li>Brown patterned and rose gold dial</li>
                <li>Leather strap,</li>
                <li>Slightly domed mineral crystal</li>
                <li>Water resistant to 99 feet (30 M): withstands rain and splashes of water, but not showering or submersion</li>
                <li>Case Diameter: 42 mm ; Case Thickness: 11.2 mm ; 3-year Limited Warranty</li>
            </ul>
            <a href="#" class="more text-blue">Xem thêm <i class="fas fa-caret-down"></i></a>';
        return Html::tag('div', $html, ['class' => 'product-full-info']);
    }

    protected function renderOptionBox()
    {
        $variationOptions = $this->item->variation_options;
        $options = [];
        foreach ($variationOptions as $index => $variationOption) {
            /* @var $variationOption \common\products\VariationOption */
            $optionHtml = Html::label($variationOption->name);
            if (($optionImages = $variationOption->images_mapping) !== null && count($optionImages) > 0) {
                $lis = [];
                foreach ($optionImages as $optionImage) {
                    /* @var $optionImage \common\products\VariationOptionImage */
                    $image = $optionImage->images[0];
                    /* @var $image \common\products\Image */
                    $lis[] = '<li><span>' . Html::img($image->thumb, [
                            'alt' => $optionImage->value,
                            'style' => 'width:100px'
                        ]) . '</span></li>';
                }
                $optionHtml .= Html::tag('ul', implode("\n", $lis), [
                    'class' => 'style-list'
                ]);
                $options[] = Html::tag('div', $optionHtml, ['class' => 'option-box']);
            } else {
                $optionHtml .= Html::dropDownList('dropdown',null,$variationOption->values,[]);
                $options[] = Html::tag('div', $optionHtml, ['class' => 'option-box form-inline']);
            }

        }
        $options = implode("\n", $options);
        return $options;
    }

    protected function renderSlide()
    {
        return Yii::$app->controller->renderPartial('_right');
    }

    protected function renderDescription()
    {
        return Yii::$app->controller->renderPartial('_item_deception');
    }
}