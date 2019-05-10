<?php


namespace frontend\modules\checkout\controllers;


use frontend\controllers\FrontendController;

class CheckoutController extends FrontendController
{

    public $layout = '@frontend/views/layouts/checkout';

    public $step = 1;

    public function renderContent($content)
    {
        $layoutFile = $this->findLayoutFile($this->getView());
        if ($layoutFile !== false) {
            return $this->getView()->renderFile($layoutFile, [
                'content' => $content,
                'step' => $this->step,
            ], $this);
        }

        return $content;
    }
}