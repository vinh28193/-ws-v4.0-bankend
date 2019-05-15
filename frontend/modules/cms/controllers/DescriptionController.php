<?php


namespace frontend\modules\cms\controllers;


use common\products\forms\ProductDetailFrom;
use frontend\controllers\CmsController;

class DescriptionController extends CmsController
{
    public function actionIndex() {
        $form = new ProductDetailFrom();
        $form->load($this->request->getQueryParams(),'');
        if (($item = $form->detail()) === false) {
            return $this->render('@frontend/views/common/item_error', [
                'errors' => $form->getErrors()
            ]);
        }
        return $this->renderPartial('index', [
            'item' => $item
        ]);
    }
}