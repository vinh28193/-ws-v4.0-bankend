<?php


namespace frontend\widgets\item;

use Yii;
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
        $css = <<< CSS
        .detail-block-2,.product-viewed {
            border-top: none;
            border-bottom: solid 1px #ebebeb;
        } 
        .detail-block {
          display: flex;
          border-bottom: 1px solid #ebebeb;
          padding-bottom: 12px;
          min-height: 485px;
        }
CSS;
        $this->getView()->registerCss($css);
        if (!isset($this->slideOptions['class'])) {
            Html::addCssClass($this->slideOptions, 'item-slider');
        }
        Html::addCssClass($this->priceOptions, 'price');
        Html::addCssClass($this->options, 'detail-content');
        $this->prepareItem();
        $this->registerClientScript();
        Pjax::begin([
            'options' => $this->options,
        ]);
    }

    public function run()
    {
        parent::run();
        echo $this->renderEntries();
        Pjax::end();
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
            'paymentUrl' => Url::to('/checkout/cart/add'),
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
            'quantity_sold' => $this->item->quantity_sold,
            'available_quantity' => $this->item->available_quantity,
            'images' => $this->item->primary_images,
        ]);
        $options = Json::htmlEncode($this->getClientOptions());
        $view = $this->getView();
        ItemDetailAsset::register($view);
        $view->registerJs("jQuery('#$id').wsItem($params,$options);", $view::POS_END);
        $view->registerJs("console.log($('#$id').wsItem('data'));", $view::POS_END);
        $js =<<<JS
         var client = new ClientJS();
	     var _fingerprint = client.getFingerprint(); 
	     console.log("fingerprint: " + _fingerprint );
	     var SendInfo = { fingerprint: _fingerprint, _csrf:9999 };
	     setTimeout(function()
            {
                 ws.ajax('/ebay/item/favorite', {
                                    type: 'POST',
                                    data: JSON.stringify(SendInfo),
                                    dataType: 'json', 
                                    contentType: "application/json; charset=utf-8",
                                    success: function (response) {
                                         console.log("done");
                                    } 
                                }, false);
            }, 3000);
JS;
        // $view->registerJs( $js,$view::POS_END);

    }

    protected function renderEntries()
    {

        $entries = Html::tag('div', $this->renderDetailBlock(), [
            'class' => 'col-md-9'
        ]);
        $entries .= Html::tag('div', $this->renderPaymentOption(), [
            'class' => 'col-md-3'
        ]);
        $entries .= Html::tag('div', $this->renderDescription('extra'), [
            'class' => 'col-md-12'
        ]);
        return $entries;
    }

    protected function renderDetailBlock()
    {
        $detailBlock = Html::beginTag('div', ['class' => 'detail-block']);
        $detailBlock .= $this->renderSlide();
        $detailBlock .= $this->renderFullInfo();
        $detailBlock .= Html::endTag('div');
        $detailBlock .= $this->renderRelateProduct();
        $detailBlock .= $this->renderDescription('mini');
        return $detailBlock;
    }

    public function renderPaymentOption()
    {
        return $this->render('payment', [
            'images' => $this->item->primary_images,
            'item' => $this->item
        ]);
    }

    public function renderFullInfo()
    {
        return $this->render('item/info', [
            'item' => $this->item
        ]);
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
        return $this->render('item/slide', [
            'item' => $this->item
        ]);
    }

    protected function renderRelateProduct()
    {
        return $this->render('item/relate_product', [
            'item' => $this->item
        ]);
    }

    protected function renderDescription($type)
    {
        return $this->render('item/description', ['item' => $this->item, 'type' => $type]);

    }

    public function getQueryParams()
    {
        return ($request = Yii::$app->getRequest()) instanceof Request ? $request->getQueryParams() : [];
    }
}
