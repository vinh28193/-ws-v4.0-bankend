<?php

namespace common\products;

/**
 * @property Option[] $options_group
 * @property Image[] $images
 */
class VariationMapping extends \yii\base\BaseObject
{

    public $variation_sku;
    public $variation_price;
    public $variation_start_price;
    public $available_quantity;
    public $quantity_sold;
    public $options_group = [];
    public $images = [];
    public $image_diff;

    public function init()
    {
        $this->setImages();
        $this->setOptionGroup();
    }

    function setImages()
    {
        $rs = [];
        if (count($this->images) > 0)
            foreach ($this->images as $img) {
                $it = new Image($img);
                $rs[] = $it;
            }
        $this->images = $rs;
    }

    function setOptionGroup()
    {
        $rs = [];
        if (count($this->options_group) > 0)
            foreach ($this->options_group as $option) {
                $it = new Option($option);
                $rs[] = $it;
            }
        $this->options_group = $rs;
    }

}