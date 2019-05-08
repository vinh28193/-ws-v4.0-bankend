<?php


namespace frontend\widgets\item;

use Yii;
use frontend\assets\ItemAsset;
use yii\bootstrap\Widget;
use common\products\BaseProduct;
use yii\base\InvalidConfigException;
use yii\bootstrap4\Dropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\Request;
use yii\widgets\Pjax;

class ItemDetailWidget extends Widget
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

    public $slideOptions = [
        'class' => 'thumb-slider'
    ];

    public $priceOptions = [];

    public function init()
    {
        parent::init();
        if ($this->item === null || !$this->item instanceof BaseProduct) {
            throw new InvalidConfigException(get_class($this) . "::product must be instance of: " . BaseProduct::className());
        }
        if (!isset($this->slideOptions['class'])) {
            Html::addCssClass($this->slideOptions, 'item-slider');
        }
        Html::addCssClass($this->priceOptions, 'price');
        $this->prepareItem();
        $this->registerClientScript();
    }

    public function run()
    {
        parent::run();
        echo $this->renderEntries();
    }

    protected function prepareItem()
    {
        usort($this->item->variation_options, function ($a, $b) {
            if (empty($a->images_mapping)) {
                return -1;
            } else {
                return 1;
            }
        });
    }

    public function getClientOptions()
    {

        $options = [
            'ajaxUrl' => Url::toRoute('item/variation'),
            'ajaxMethod' => 'POST',
            'queryParams' => $this->getQueryParams(),
            'priceCssSelection' => $this->priceOptions['class'],
            'slideCssSelection' => $this->slideOptions['class']
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
        $params = Json::htmlEncode([
            'id' => $this->item->item_id,
            'sku' => $this->item->item_sku,
            'seller' => $this->item->getSeller(),
            'condition' => $this->item->getIsNew(),
            'type' => $this->item->getItemType(),
            'variation_mapping' => $this->item->variation_mapping,
            'variation_options' => $this->item->variation_options,
            'sellers' => $this->item->providers,
            'conditions' => $this->item->condition,
            'images' => $this->item->primary_images,
        ]);
        $options = Json::htmlEncode($this->getClientOptions());
        $view = $this->getView();
        ItemAsset::register($view);
        $view->registerJs("jQuery('#$id').wsItem($params,$options);", $view::POS_END);
        $view->registerJs("console.log($('#$id').wsItem('data'));", $view::POS_END);
    }

    protected function renderEntries()
    {

        $entries = Html::beginTag('div', $this->options);
        $entries .= Html::tag('div', $this->renderDetailBlock(), [
            'class' => 'col-md-9'
        ]);
        $entries .= Html::tag('div', $this->renderPaymentOption(), [
            'class' => 'col-md-3'
        ]);
        $entries .= Html::endTag('div');
        return $entries;
    }

    protected function renderDetailBlock()
    {
        $detailBlock = Html::beginTag('div', ['class' => 'detail-block']);
        $detailBlock .= $this->renderSlide();
        $detailBlock .= $this->renderFullInfo();
        $detailBlock .= Html::endTag('div');
        return $detailBlock;
    }

    public function renderPaymentOption()
    {
        return $this->render('payment', [
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
        $currency = Html::tag('span', 'đ', ['class' => 'currency']);
        $price = Html::tag('strong', $this->item->getLocalizeTotalPrice() . $currency, ['class' => 'text-orange']);
        $style = 'display:none';
        if ($salePercent > 0 && ($startPrice = $this->item->getLocalizeTotalStartPrice()) > 0) {
            $style = 'display:block';
        }
        $price .= Html::tag('b', $this->item->getLocalizeTotalStartPrice() . $currency, array_merge(['class' => 'old-price'], ['style' => $style]));
        $price .= Html::tag('span', $salePercent, array_merge(['class' => 'save'], ['style' => $style]));
        $html .= Html::tag('div', $price, $this->priceOptions);
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
        if (empty($variationOptions)) {
            return '';
        }
        $optionBoxs = [];
        foreach ($variationOptions as $index => $variationOption) {
            /* @var $variationOption \common\products\VariationOption */
            $optionBoxOptions = [];
            $name = $variationOption->name;
            $elementOptions = [
                'id' => Inflector::camel2id($name)
            ];
            $label = Html::label($name, isset($elementOptions['id']) ? $elementOptions['id'] : null, []);
            if (($optionImages = $variationOption->images_mapping) !== null && count($optionImages) > 0) {
                $lines = [];
                foreach ($optionImages as $i => $optionImage) {
                    /* @var $optionImage \common\products\VariationOptionImage */
                    $image = $optionImage->images[0];
                    /* @var $image \common\products\Image */
                    $lines[] = '<li><span type="spanList" data-index="' . $i . '" data-value="' . $optionImage->value . '">' .
                        Html::img($image->thumb, [
                            'alt' => $optionImage->value,
                            'style' => 'width:100px'
                        ]) . '</span></li>';
                }
                Html::addCssClass($elementOptions, 'style-list');
                $element = Html::tag('ul', implode("\n", $lines), $elementOptions);
                Html::addCssClass($optionBoxOptions, 'option-box');
//                $element = Html::radioList($name, null, $list2, $elementOptions);

            } else {
                $elementOptions = ArrayHelper::merge([
                    'type' => 'dropDown'
                ], $elementOptions);
                $element = Html::dropDownList($variationOption->name, null, $variationOption->values, $elementOptions);
                Html::addCssClass($optionBoxOptions, 'option-box form-inline');

            }
            Html::addCssClass($optionBoxOptions, 'variation_select');
            $optionBoxOptions = ArrayHelper::merge([
                'data-ref' => $name,
            ], $optionBoxOptions);
            $optionBoxs[] = Html::tag('div', $label . $element, $optionBoxOptions);

        }
        return implode("\n", $optionBoxs);
    }

    protected function renderSlide()
    {
        return Html::tag('div', '', $this->slideOptions);
    }

    protected function renderDescription()
    {
        return '';
    }

    public function getQueryParams()
    {
        return ($request = Yii::$app->getRequest()) instanceof Request ? $request->getQueryParams() : [];
    }
}