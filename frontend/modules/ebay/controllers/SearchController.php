<?php


namespace frontend\modules\ebay\controllers;

use Yii;
use common\products\forms\ProductSearchForm;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

class SearchController extends EbayController
{

    public function actionIndex()
    {
        $queryParams = Yii::$app->getRequest()->getQueryParams();
        $form = new ProductSearchForm();
        $form->load($queryParams);
        $form->type = 'ebay';
        $this->portalTitle = Yii::t('frontend', "Search ebay: {keyword}", [
            'keyword' => Html::decode($form->keyword)
        ]);;
        Yii::info($form->getAttributes(), __METHOD__);
        if (!($results = $form->search()) || (isset($results['products']) && $results['products'] === 0)) {
//            return $this->renderPartial('@frontend/views/common/no_search_results',['form' => $form]);
        }
        return $this->render('index', [
            'results' => $results,
            'form' => $form
        ]);
    }
}