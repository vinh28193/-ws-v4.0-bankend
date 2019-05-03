<?php

namespace frontend\widgets\cms;

class SlideWidget extends WeshopBlockWidget
{

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        parent::run();
        echo $this->render('slide');
    }
}