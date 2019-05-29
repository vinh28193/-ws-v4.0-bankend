<?php

namespace frontend\widgets\cms;

class WeshopBlockWidget extends \yii\bootstrap4\Widget
{

    const BLOCK5 = 'BLOCK5';
    const BLOCK_CONTENT = 'BLOCK_CONTENT';
    const BLOCK7 = 'BLOCK7';
    const BLOCK8 = 'BLOCK8';
    const LANDING = 'LANDING';
    const IMG5 = 'IMG5';
    const IMG7 = 'IMG7';
    const BRAND_SLIDER = 'BRAND_SLIDER';
    const LANDING_IMG_GIRD = 'LANDING_IMG_GIRD';
    const LANDING_IMG_FLUID = 'LANDING_IMG_FLUID';
    const LANDING_IMG_LIST = 'LANDING_IMG_LIST';
    const LANDING_SLIDE_FLUID = 'LANDING_SLIDE_FLUID';
    const LANDING_SLIDE = 'LANDING_SLIDE';
    const LANDING_PRODUCT_CAROUSEL = 'LANDING_PRODUCT_CAROUSEL';
    const LANDING_PRODUCT_CAROUSEL_THUMB_NAILS = 'LANDING_PRODUCT_CAROUSEL_THUMB_NAILS';
    const LANDING_PRODUCT_BANNER_CENTER = 'LANDING_PRODUCT_BANNER_CENTER';
    const LANDING_BRAND_SLIDER = 'LANDING_BRAND_SLIDER';
    const BLOCK_BLACK_FRIDAY = 'BLOCK_BLACK_FRIDAY';
    // NEW LANDING JUNE
    const JUN_LANDING_4_IMG_2_ROW = 'JUN_LANDING_4_IMG_2_ROW';
    const JUN_LANDING_3_IMG_1_ROW = 'JUN_LANDING_3_IMG_1_ROW';
    const JUN_LANDING_4_IMG_1_ROW = 'JUN_LANDING_4_IMG_1_ROW';
    const JUN_LANDING_6_IMG_1_ROW = 'JUN_LANDING_6_IMG_1_ROW';
    const JUN_LANDING_5_IMG_2_ROW_1_BIG = 'JUN_LANDING_5_IMG_2_ROW_1_BIG';
    const JUN_LANDING_5_IMG_2_ROW_1_BANNER = 'JUN_LANDING_5_IMG_2_ROW_1_BANNER';
    CONST JUN_LANDING_7_IMG_2_ROW = 'JUN_LANDING_7_IMG_2_ROW';
    const UK_REQUEST_CALCULATE = 'UK_REQUEST_CALCULATE';

    /**
     * @var \common\models\cms\WsBlock
     */
    public $block;

    /**
     * @var bool
     */
    public $iphoneOld = true;

    public function render($view, $params = [])
    {
        $params = array_merge(['iphoneOld' => $this->iphoneOld],$params);
        return parent::render($view, $params);
    }
}