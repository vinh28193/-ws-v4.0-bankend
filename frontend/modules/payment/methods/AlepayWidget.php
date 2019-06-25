<?php


namespace frontend\modules\payment\methods;


class AlepayWidget extends MethodWidget
{

    public function init()
    {
        parent::init();
        $css = <<< CSS
.select-method {
    height: 33px;
    background-color: rgb(242, 243, 245);
    color: rgb(85, 85, 85);
    width: 250px;
    font-size: 12px;
    text-align: left;
    position: relative;
    border-radius: 3px;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(235, 235, 235);
    border-image: initial;
}
CSS;
        $this->getView()->registerCss($css);
    }

    public function run()
    {
        parent::run();
        return $this->render('alepay');
    }
}