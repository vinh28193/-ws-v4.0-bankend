<?php


namespace frontend\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\Widget;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\Pjax;
use yii\helpers\Url;

class WsLazyCMSWidget extends Widget
{

    public $totalPage = 5;

    public $content;

    public $ajaxUrl;


    public function init()
    {
        parent::init();
        if($this->ajaxUrl === null){
            $this->ajaxUrl = Yii::$app->request->url;
        }
        $this->registerLazyJs();
    }

    public function run()
    {
        parent::run();

        echo $this->renderLazyContent();
    }


    public function registerLazyJs()
    {
        $func = '';
        for ($k = 2; $k <= $this->totalPage; $k++) {
            $url = $this->ajaxUrl;
            $url .= '?p='.$k;
            $func .= "
    \nproductBlock{$k}: function (element){
        $.ajax({
            url: '{$url}',
            method: 'GET',
            async: true,
            success: function (res) {
                element.html(res);
//                element.load();
                $('img.lazy').lazyload({
                    effect: 'fadeIn',
                    effectTime: 2000,
                    threshold: 100,
                });
            }
        });
    },";
        }
        $js =<<<JS
$(".lazy-content").Lazy({
    // custom loaders
    $func
});
JS;
        $this->getView()->registerJs($js, View::POS_READY);

    }

    protected function renderLazyContent()
    {
        $content = $this->renderStaticContent();
        for ($k = 2; $k <= $this->totalPage; $k++) {
            $content .= '<div class="lazy-content" data-loader="productBlock' . $k . '"></div>';
        }
        return $content;
    }

    protected function renderStaticContent(){
        return Yii::$app->controller->renderPartial('_content',[
            'content' => $this->content
        ]);
    }
}