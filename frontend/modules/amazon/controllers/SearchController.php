<?php


namespace frontend\modules\amazon\controllers;


use common\products\forms\ProductSearchForm;
use Yii;

class SearchController extends AmazonController
{


    public function actionIndex()
    {
        $queryParams = Yii::$app->getRequest()->getQueryParams();
        $form = new ProductSearchForm();
        $form->load($queryParams);
        $form->type = 'amazon';
        Yii::info($form->getAttributes(), __METHOD__);
        if (($results = $form->search()) === false || (isset($results['products']) && $results['products'] === 0)) {
            return $this->renderPartial('@frontend/common/no_search_results');
        }
        return $this->render('index', [
            'results' => $results,
            'form' => $form
        ]);
    }
}