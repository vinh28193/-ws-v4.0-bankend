<?php


namespace frontend\widgets\cms;


class SevenImgWidget extends WeshopBlockWidget
{

    public function run()
    {
        parent::run();
        $images = isset($this->block['images']) ? $this->block['images'] : [];
        $grid = isset($this->block['grid']) ? $this->block['grid'] : [];
        $categories = isset($this->block['categories']) ? $this->block['categories'] : [];
        return $this->render("seven_img", [
            'block' => $this->block['block'],
            'categories' => $categories,
            'images' => $images,
            'grid' => $grid,
        ]);
    }
}