<?php
/**
 * Created by PhpStorm.
 * User: Thaim
 * Date: 9/11/2017
 * Time: 1:27 PM
 */

namespace common\models\weshop\subproduct;

/**
 * @property Option[] $options_group
 * @property Image[] $images
 */
class VariationMapping
{
    public function __construct($data)
    {
        $productAttr = get_object_vars($this);
        foreach ($data as $k => $v) {
            foreach ($productAttr as $k1 => $v1) {
                if (!is_object($k1))
                    if ($k == $k1) {
                        $this->$k1 = $v;
                    }
            }
        }
        $this->setImages();
        $this->setOptionGroup();
    }

    public $variation_sku;
    public $variation_price;
    public $variation_start_price;
    public $available_quantity;
    public $quantity_sold;
    public $options_group=[];
    public $images=[];
    public $image_diff;

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