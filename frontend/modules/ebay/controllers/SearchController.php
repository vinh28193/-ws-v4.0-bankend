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
        $form->type = 'amazon';
        $form->load($queryParams);
        $results = $form->search();
        var_dump($results);die;
        return $this->render('index', [
            'results' => $results,
            'form' => $form
        ]);
    }
}