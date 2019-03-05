<?php
/**
 * Created by PhpStorm.
 * User: Thaim
 * Date: 9/11/2017
 * Time: 1:37 PM
 */

namespace common\models\weshop\subproduct;

/**
 * @property string $value;
 * @property Image[] $images;
 */
class VariationOptionImage
{
    public function __construct($data=null)
    {
        if($data!=null){
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
        }

    }

    public $value;
    public $images;

    public function setImages()
    {
        $rs = [];
        if (count($this->images) > 0)
            foreach ($this->images as $img) {
                $it = new Image($img);
                $rs[] = $it;
            }
        $this->images = $rs;
    }
}