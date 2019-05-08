<?php


namespace frontend\controllers;


use common\products\BaseProduct;

class PortalController extends FrontendController
{
    public $layout = '@frontend/views/layouts/portal';

    public $portal = BaseProduct::TYPE_EBAY;

    public function renderContent($content)
    {
        $layoutFile = $this->findLayoutFile($this->getView());
        if ($layoutFile !== false) {
            return $this->getView()->renderFile($layoutFile, [
                'content' => $content,
                'portal' => $this->portal,
            ], $this);
        }

        return $content;
    }
}