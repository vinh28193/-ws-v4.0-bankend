<?php
namespace frontend\modules\account\views\widgets;

class HeaderContentWidget extends \yii\base\Widget
{
    public $title;
    public $stepUrl = [];
    public function run()
    {
        return $this->render('header-content',[
            'title' => $this->title,
            'stepUrl' => array_merge(['Trang chủ' => '/','My WeShop' => '/my-weshop.html'],$this->stepUrl),
        ]);
    }
}