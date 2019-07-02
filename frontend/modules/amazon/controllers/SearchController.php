<?php


namespace frontend\modules\amazon\controllers;


use common\products\forms\ProductSearchForm;
use Yii;
use yii\helpers\Html;

class SearchController extends AmazonController
{


    public function actionIndex()
    {
        $queryParams = $this->request->getQueryParams();
        $form = new ProductSearchForm();
        $form->load($queryParams);
        $form->type = 'amazon';
        $this->portalTitle = Yii::t('frontend','{keyword} - Shopping US Amazon, eBay - Weshop {web_name}',['keyword' => $form->keyword, 'web_name' => Yii::$app->storeManager->getName()]);
        $this->portalDescription = Yii::t('frontend','{keyword} - Shopping US Amazon, eBay  easily immediately via Weshop {web_name}  to get the product within 7-15 days with many attractive offers, support goods inspection, direct consultation before purchase!',[
            'keyword' => $form->keyword,
            'web_name' => Yii::$app->storeManager->getName()
        ]);
        $this->registerAllMetaTagLinkTag();

        Yii::info($form->getAttributes(), __METHOD__);
        if (($results = $form->search()) === false || (isset($results['products']) && $results['products'] === 0)) {
            return $this->render('@frontend/views/common/no_search_results');
        }
        return $this->render('index', [
            'results' => $results,
            'form' => $form
        ]);
    }
}