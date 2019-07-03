<?php

namespace frontend\widgets\cms;

use common\models\cms\PageService;
use yii\helpers\Html;
use yii\helpers\Json;

class SlideWidget extends WeshopBlockWidget
{

    public $page;

    public $list_images;

    public $owlCarouselOptions = [];

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, 'owl-carousel owl-theme');
        $this->getImages();
        $this->registerClientScript();

    }

    public function run()
    {
        parent::run();
        return $this->renderItems();
    }

    protected function registerClientScript()
    {
        $view = $this->getView();
        $id = $this->options['id'];
        $options = Json::htmlEncode($this->owlCarouselOptions);
        $view->registerJs("$('#$id').owlCarousel($options);");
    }

    private $_images = [];

    public function getImages()
    {
        if($this->list_images){
            return $this->list_images;
        }
        if (empty($this->_images)) {
            $this->_images = PageService::getSlider($this->page->id);
        }
        return $this->_images;
    }

    protected function renderItems()
    {
//        $html = Html::beginTag('div', $this->options);
//        foreach ($this->getImages() as $image) {
//            $html .= $this->renderItem($image);
//        }
//        $html .= Html::endTag('div');
        return $this->render('slide',['images' => $this->getImages()]);
    }

    protected function renderItem($item)
    {
        $src = $item['domain'] . $item['origin_src'];
        $img = Html::img($src, [
            'alt' => $item['name'],
            'title' => $item['name'],
        ]);
        $content = Html::beginTag('div', ['class' => 'container']);
        $content .= Html::a($img, $item['link']);
        $content .= Html::endTag('div');
        return Html::tag('div', $content, ['class' => 'item']);
    }
}