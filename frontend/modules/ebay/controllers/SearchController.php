<?php


namespace frontend\modules\ebay\controllers;

use Yii;
use common\products\forms\ProductSearchForm;
use yii\data\ArrayDataProvider;

class SearchController extends EbayController
{

    public function actionIndex()
    {
        $request = Yii::$app->getRequest();
        if($request->isAjax){
            $queryParams = $request->getQueryParams();
            $form = new ProductSearchForm();
            $form->load($queryParams);
            $form->type = 'ebay';
            if(($results = $form->search()) === false || (isset($results['products']) && $results['products'] === 0)){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $this->renderPartial('@frontend/common/no_search_results');
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->renderPartial('content/content', [
                'results' => $results,
                'form' => $form
            ]);
        }
        return $this->render('index');
    }
}