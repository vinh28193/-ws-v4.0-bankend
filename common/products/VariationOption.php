<?php

namespace common\products;

/**
 * @property String $id;
 * @property String $name;
 * @property String[] $values;
 * @property VariationOptionImage[] $images_mapping;
 */

class VariationOption extends \yii\base\BaseObject
{

    public $id;
    public $name;
    public $values;
    public $images_mapping = [];

    public function init()
    {
        $this->setImagesMapping();
    }
    public function setId(){
        $this->id = md5($this->name);
        return $this->id;
    }
    public function setImagesMapping()
    {
        $rs = [];
        if (count($this->images_mapping) > 0)
            foreach ($this->images_mapping as $img) {
                $it = new VariationOptionImage();
                $it->value = preg_replace('/[^A-Za-z0-9\-\. ][\p{Han}\p{Katakana}\p{Hiragana}]/', '', $img['value']);
                $it->images = $img['images'];
                $it->setImages();
                $rs[] = $it;
            }
        $this->images_mapping = $rs;
    }
}