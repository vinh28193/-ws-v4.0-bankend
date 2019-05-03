<?php


namespace frontend\widgets;

use yii\helpers\Html;
use yii\bootstrap4\Widget;
use frontend\widgets\cms\WeshopBlockWidget;
use frontend\widgets\cms\HotDealWidget;
use frontend\widgets\cms\FiveWidget;
use frontend\widgets\cms\FiveImgWidget;
use frontend\widgets\cms\SevenWidget;
use frontend\widgets\cms\SevenImgWidget;

class WsStaticCMSWidget extends Widget
{

    /**
     * @var array
     */
    public $data;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        parent::run();
        echo $this->renderContainer();
    }

    protected function renderContainer()
    {
        $content = '';
        $block5 = [];
        $checkBlock5 = false;
        foreach ($this->data as $key => $value) {
            $type = $value['block']['type'];
            if ($type == WeshopBlockWidget::BLOCK5){
                $block5[] = $value;
            }
            switch ($type) {
                case WeshopBlockWidget::LANDING:
                    $content .= HotDealWidget::widget([
                        'block' => $value,
                    ]);
                    break;
                case WeshopBlockWidget::BLOCK7:
                    $content .= SevenWidget::widget([
                        'block' => $value,
                    ]);
                    break;
                case WeshopBlockWidget::IMG5:
                    $content .= FiveImgWidget::widget([
                        'block' => $value,
                    ]);
                    break;
                case WeshopBlockWidget::IMG7 :
                    $content .= SevenImgWidget::widget([
                        'block' => $value,
                    ]);
                    break;
                case WeshopBlockWidget::BLOCK5:
                    if (!$checkBlock5) {
                        $checkBlock5 = true;
                        $content .= '{replace_block_5}';
                    }
                    break;
                default:
                    break;
            }
        }
        $contentBlock5 = '';
        if(count($block5) > 0){
            $contentBlock5 = '<div class="product-block amazon block-column">
                            <div class="container">
                                <div class="row">';
            foreach ($block5 as $item) {
                $contentBlock5 .= FiveWidget::widget(['block' => $item]);
            }
            $contentBlock5 .= '</div></div></div>';
        }

        $content = str_replace('{replace_block_5}', $contentBlock5, $content);
        return $content;
    }
}