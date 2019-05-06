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

    public function init()
    {
        parent::init();
        if ($this->item === null || !$this->item instanceof BaseProduct) {
            throw new InvalidConfigException(get_class($this) . "::product must be instance of: " . BaseProduct::className());
        }
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
            'ajaxUrl' => 'http://weshop-4.0.frontend.vn/ebay/item/variation',
            'ajaxMethod' => 'POST',
            'queryParams' => $this->getQueryParams()
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
                $lis = [];
                $list2 = [];
                foreach ($optionImages as $optionImage) {
                    /* @var $optionImage \common\products\VariationOptionImage */
                    $image = $optionImage->images[0];
                    /* @var $image \common\products\Image */
                    $list2[$optionImage->value] = $optionImage->value;
                    $lis[] = '<li><span type="spanList" data-value="'.$optionImage->value.'">' .
                        Html::img($image->thumb, [
                            'alt' => $optionImage->value,
                            'style' => 'width:100px'
                        ]) . '</span></li>';
                }
                Html::addCssClass($elementOptions, 'style-list');
                $element = Html::tag('ul', implode("\n", $lis), $elementOptions);
                Html::addCssClass($optionBoxOptions, 'option-box');
//                $element = Html::radioList($name, null, $list2, $elementOptions);

            } else {
                $elementOptions = ArrayHelper::merge([
                    'type' => 'dropDown'
                ], $elementOptions);
                $items = $variationOption->values;
                $element = Html::dropDownList($variationOption->name, null, array_combine($items, $items), $elementOptions);
                Html::addCssClass($optionBoxOptions, 'option-box form-inline');

            }
            Html::addCssClass($optionBoxOptions, 'variation_select');
            $optionBoxOptions = ArrayHelper::merge([
                'data-ref' => $name,
            ], $optionBoxOptions);
            $optionBoxs[] = Html::tag('div', $label . $element, $optionBoxOptions);

        }
        return implode("\n", $optionBoxs);;
    }

    protected function renderSlide()
    {
        return $this->render('item/slide',[           
            'images' => $this->item->primary_images,
            'alt'    => $this->item->item_name
          ]
    );
    }

    protected function renderDescription()
    {
        return '';
    }

    public function getQueryParams(){
       return ($request = Yii::$app->getRequest()) instanceof Request ? $request->getQueryParams() : [];
    }
}