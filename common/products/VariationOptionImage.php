<?php

namespace common\products;

/**
 * @property string $value;
 * @property Image[] $images;
 */
class VariationOptionImage extends \yii\base\BaseObject
{

    public $value;
    public $images;

    public function init()
    {
        $this->setImages();
    }

    public function setImages()
    {
        $rs = [];
        if (!empty($this->images))
            foreach ($this->images as $img) {
                $it = new Image($img);
                $rs[] = $it;
            }
        $this->images = $rs;
    }
}