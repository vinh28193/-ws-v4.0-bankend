<?php
/**
 * Created by PhpStorm.
 * User: Thaim
 * Date: 9/11/2017
 * Time: 1:27 PM
 */

namespace common\models\weshop\subproduct;

/**
 * @property String $name;
 * @property String[] $values;
 * @property VariationOptionImage[] $images_mappings;
 */

class VariationOption
{
    public function __construct($data=null)
    {
        if($data!=null)
        {
            $productAttr = get_object_vars($this);
            foreach ($data as $k => $v) {
                foreach ($productAttr as $k1 => $v1) {
                    if (!is_object($k1))
                        if ($k == $k1) {
                            $this->$k1 =$v;
                        }
                }
            }
            $this->setImagesMapping();
        }

    }

    public $name;
    public $values;
    public $images_mapping=[];

    public function setImagesMapping()    {
        $rs = [];
        if (count($this->images_mapping) > 0)
            foreach ($this->images_mapping as $img) {
                $it = new VariationOptionImage();
                $it->value=preg_replace('/[^A-Za-z0-9\-\. ][\p{Han}\p{Katakana}\p{Hiragana}]/', '', $img['value']) ;
                $it->images=$img['images'];
                $it->setImages();
                $rs[] = $it;
            }
        $this->images_mapping = $rs;
    }
}