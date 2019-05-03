<?php


namespace frontend\modules\ebay\controllers;

use Yii;
use common\products\forms\ProductSearchForm;
use yii\data\ArrayDataProvider;

class SearchController extends EbayController
{

    public function actionIndex()
    {
        $queryParams = Yii::$app->getRequest()->getQueryParams();
        $form = new ProductSearchForm();
        $form->type = 'ebay';
        $form->load($queryParams);
        if(($results = $form->search()) === false || (isset($results['products']) && $results['products'] === 0)){
            return $this->render('@frontend/common/no_search_results');
        }
        return $this->render('index', [
            'results' => $results,
            'form' => $form
        ]);
    }
}