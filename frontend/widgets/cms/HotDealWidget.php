<?php


namespace frontend\widgets\cms;


class HotDealWidget extends WeshopBlockWidget
{

    public function run()
    {
        parent::run();
        echo $this->render('hot_deal');
    }
}