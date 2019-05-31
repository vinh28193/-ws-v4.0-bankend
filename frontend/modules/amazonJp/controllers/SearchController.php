<?php


namespace frontend\modules\amazonJp\controllers;


use common\products\forms\ProductSearchForm;
use Yii;
use yii\helpers\Html;

class SearchController extends AmazonJpController
{

    public function actionIndex()
    {
        $queryParams = Yii::$app->getRequest()->getQueryParams();
        $form = new ProductSearchForm();
        $form->load($queryParams);
        $form->type = 'amazon-jp';
        $this->portalTitle = Yii::t('frontend', "Search amazon japan: {keyword}", [
            'keyword' => Html::decode($form->keyword)
        ]);
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