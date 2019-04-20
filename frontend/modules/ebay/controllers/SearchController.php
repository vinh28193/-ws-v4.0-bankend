<?php


namespace frontend\modules\ebay\controllers;

use Yii;
use common\products\forms\ProductSearchForm;

class SearchController extends EbayController
{

    public function actionIndex()
    {
        $queryParams = Yii::$app->getRequest()->getQueryParams();
        $form = new ProductSearchForm();
        $form->type = 'ebay';
        $form->load($queryParams);
        $results = $form->search();
        return $this->render('index', [
            'results' => $results,
            'form' => $form
        ]);
    }
}